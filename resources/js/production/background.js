import * as THREE from 'three';
import { createCameraRig, createLoop } from './animation.js';
import { CAMERA_MOVEMENT, LINE, PALETTES, STATIONS, prefersReducedMotion, resolveOptions } from './config.js';
import { createConveyor } from './conveyor.js';
import { createFactory } from './factory.js';
import { createInteractions, createLabels } from './interactions.js';
import { animateStation, createStations, normalizeStatus, setStationStatus } from './machines.js';
import { applyPalette, createGeometries, createMaterials, disposeAll, part } from './materials.js';
import { createProductFlow } from './products.js';
import { applyScenePalette, createSceneContext, resizeContext } from './scene.js';

/*
|--------------------------------------------------------------------------
| Fondo 3D de línea de producción
|--------------------------------------------------------------------------
| initProductionBackground(container, { mode: 'login' | 'dashboard', ... })
|
| Devuelve un controlador:
|   setData(productionData)          → estados/velocidad desde Laravel
|   setStationStatus(id, status)     → running | warning | stopped | maintenance
|   setTheme('dark' | 'light' | 'auto')
|   pause() / resume() / destroy()
*/

function webglAvailable() {
    try {
        const canvas = document.createElement('canvas');

        return !!(window.WebGLRenderingContext && (canvas.getContext('webgl2') || canvas.getContext('webgl')));
    } catch {
        return false;
    }
}

function resolveThemeName(theme) {
    if (theme === 'dark' || theme === 'light') return theme;

    return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
}

export function initProductionBackground(container, options = {}) {
    if (!container) return null;

    if (!webglAvailable()) {
        container.classList.add('is-fallback');

        return null;
    }

    const opts = resolveOptions(options);
    let themeSetting = opts.theme;
    let themeName = resolveThemeName(themeSetting);
    let palette = PALETTES[themeName];

    container.dataset.theme = themeName;

    const geometries = createGeometries(opts.isMobile);
    const materials = createMaterials(palette);
    const derivedBelts = [];

    const ctx = createSceneContext(container, opts, palette);
    const { scene, camera, renderer, lights } = ctx;
    const shadows = opts.shadows;
    const build = { geometries, materials, shadows };

    /*
    | Planta
    */
    const mixer = STATIONS.find((s) => s.type === 'mixer');
    const factory = createFactory({ ...build, density: opts.densityConfig, mixerX: mixer?.x ?? -12.5 });
    scene.add(factory.group);

    /*
    | Línea principal
    */
    const mainConveyor = createConveyor({
        ...build,
        beltMaterial: materials.belt,
        x0: LINE.startX,
        x1: LINE.endX,
        z: LINE.z,
        height: LINE.height,
        width: LINE.width,
    });
    scene.add(mainConveyor.group);

    const processing = STATIONS.find((s) => s.id === 'processing');
    const packaging = STATIONS.find((s) => s.id === 'packaging');
    const productY = LINE.height + 0.03;

    const mainFlow = createProductFlow({
        from: LINE.spawnX,
        to: LINE.despawnX,
        z: LINE.z,
        y: productY,
        spacing: opts.densityConfig.productSpacing,
        shadows,
        stages: [
            {
                at: LINE.spawnX,
                layers: [{ geometry: geometries.portion, material: materials.raw, scale: [0.5, 0.16, 0.5] }],
            },
            {
                at: processing.x,
                layers: [{ geometry: geometries.portion, material: materials.baked, scale: [0.58, 0.2, 0.58] }],
            },
            {
                at: packaging.x,
                layers: [
                    { geometry: geometries.box, material: materials.box, scale: [0.62, 0.42, 0.55] },
                    { geometry: geometries.box, material: materials.tape, scale: [0.64, 0.05, 0.14], offset: [0, 0.19, 0] },
                ],
            },
        ],
    });
    scene.add(mainFlow.group);

    /*
    | Línea secundaria (sólo densidad media/alta): cajas rumbo al almacén.
    */
    let secondary = null;

    if (opts.densityConfig.secondaryLine) {
        const beltMaterial = materials.belt.clone();
        beltMaterial.map = materials.belt.map.clone();
        derivedBelts.push(beltMaterial);

        const z = -7;
        const conveyor = createConveyor({ ...build, beltMaterial, x0: -9, x1: 17, z, height: 0.85, width: 0.9 });
        const flow = createProductFlow({
            from: -7.6,
            to: 15.6,
            z,
            y: 0.88,
            spacing: opts.densityConfig.productSpacing * 1.4,
            shadows,
            seed: 31,
            stages: [{
                at: -7.6,
                layers: [
                    { geometry: geometries.box, material: materials.box, scale: [0.55, 0.38, 0.5] },
                    { geometry: geometries.box, material: materials.tape, scale: [0.57, 0.05, 0.12], offset: [0, 0.17, 0] },
                ],
            }],
        });

        // Carcasas en los extremos (ocultan aparición/salida de cajas)
        const ends = new THREE.Group();
        for (const x of [-8.1, 16.2]) {
            ends.add(part(geometries.box, materials.body, [1.6, 1.5, 1.5], [x, 0.75, z], { shadows }));
            ends.add(part(geometries.box, materials.accent, [1.62, 0.08, 1.52], [x, 1.3, z], { shadows: false }));
        }

        scene.add(conveyor.group, flow.group, ends);
        secondary = { conveyor, flow, speed: 0.75 };
    }

    /*
    | Estaciones
    */
    const stations = createStations(STATIONS, build, { robot: opts.densityConfig.robot });
    stations.forEach((station) => scene.add(station.group));
    const stationsById = new Map(stations.map((s) => [s.id, s]));

    /*
    | Interacción y etiquetas (dashboard)
    */
    let loop = null;
    const interactions = opts.interactive
        ? createInteractions({ camera, stations, onChange: () => loop?.requestRender() })
        : null;
    const labels = opts.labels ? createLabels(opts.labelsContainer, stations) : null;

    /*
    | Estado de producción
    */
    let data = null;
    let targetSpeed = LINE.baseSpeed;
    let speed = LINE.baseSpeed;

    function setData(next) {
        if (!next) return;
        data = next;

        (next.stations ?? []).forEach((s) => {
            const station = stationsById.get(s.id);
            if (station) setStationStatus(station, s.status);
        });

        stations.forEach((s) => {
            if (!(next.stations ?? []).some((d) => d.id === s.id)) setStationStatus(s, 'running');
        });

        const halted = normalizeStatus(next.status) === 'stopped'
            || stations.some((s) => s.status === 'stopped' || s.status === 'maintenance');
        const ratio = Number(next.speed) > 0 ? Number(next.speed) / LINE.referenceSpeed : 1;

        targetSpeed = halted ? 0 : LINE.baseSpeed * THREE.MathUtils.clamp(ratio, 0.3, 2);

        loop?.requestRender();
    }

    setData(opts.data);
    speed = targetSpeed;

    /*
    | Cámara
    */
    const rig = createCameraRig(camera, opts.camera);
    const amplitude = (CAMERA_MOVEMENT[opts.cameraMovement] ?? CAMERA_MOVEMENT.subtle).amplitude * opts.movementScale;
    let scrollTarget = 0;

    const onScroll = () => {
        const max = document.documentElement.scrollHeight - window.innerHeight;
        scrollTarget = max > 0 ? THREE.MathUtils.clamp(window.scrollY / max, 0, 1) : 0;
    };

    if (opts.scrollParallax) {
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    /*
    | Frame
    */
    const stationCtx = {
        flow: mainFlow,
        speedFactor: 1,
        haloOpacity: palette.haloOpacity,
        instant: false,
    };

    const hoverColor = new THREE.Color();
    let firstFrame = true;

    function frame(t, dt, instant) {
        const reduced = loop.isStatic;

        speed += (targetSpeed - speed) * (instant ? 1 : 1 - Math.exp(-dt * 1.5));

        mainConveyor.update(dt, speed);
        mainFlow.update(dt, speed);

        if (secondary) {
            secondary.conveyor.update(dt, secondary.speed);
            secondary.flow.update(dt, secondary.speed);
        }

        const hovered = interactions?.update() ?? null;

        stationCtx.speedFactor = speed / LINE.baseSpeed;
        stationCtx.haloOpacity = palette.haloOpacity;
        stationCtx.instant = instant;
        stations.forEach((s) => animateStation(s, t, dt, stationCtx));

        // Luz de hover: se coloca sobre la estación y se enciende suavemente.
        const hoverTarget = hovered ? 6 : 0;
        if (hovered) {
            lights.hoverLight.position.set(hovered.anchor.x, 3.2, 1.8);
            lights.hoverLight.color.copy(hoverColor.set(hovered.beaconMaterial.color));
        }
        lights.hoverLight.intensity += (hoverTarget - lights.hoverLight.intensity) * (instant ? 1 : 1 - Math.exp(-dt * 8));

        rig.update(t, {
            amplitude: reduced ? 0 : amplitude,
            scrollTarget: reduced ? 0 : scrollTarget,
            instant,
            dt,
        });

        renderer.render(scene, camera);
        labels?.update(camera, ctx.width, ctx.height);

        if (firstFrame) {
            firstFrame = false;
            container.classList.add('is-ready');
        }
    }

    /*
    | Calidad adaptativa: primero baja el pixel ratio, luego (loop) los FPS.
    */
    let pixelRatio = renderer.getPixelRatio();

    function onSlow() {
        if (pixelRatio <= 1) return false;

        pixelRatio = Math.max(1, pixelRatio - 0.5);
        renderer.setPixelRatio(pixelRatio);
        resizeContext(ctx, container, opts);

        return true;
    }

    loop = createLoop({ maxFps: opts.maxFps, onFrame: frame, onSlow });

    /*
    | Movimiento reducido
    */
    const motionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
    const onMotionChange = () => loop.setStatic(motionQuery.matches);
    motionQuery.addEventListener('change', onMotionChange);

    /*
    | Tema (dashboard sigue a html.dark)
    */
    function applyTheme() {
        const next = resolveThemeName(themeSetting);
        if (next === themeName) return;

        themeName = next;
        palette = PALETTES[themeName];
        container.dataset.theme = themeName;

        applyPalette(materials, palette);
        derivedBelts.forEach((mat) => mat.color.set(palette.belt));
        applyScenePalette(ctx, palette);
        loop.requestRender();
    }

    const themeObserver = new MutationObserver(applyTheme);
    themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

    /*
    | Resize, visibilidad y pérdida de contexto
    */
    let resizeQueued = false;
    const onResize = () => {
        if (resizeQueued) return;
        resizeQueued = true;

        requestAnimationFrame(() => {
            resizeQueued = false;
            resizeContext(ctx, container, opts);
            if (opts.scrollParallax) onScroll();
            loop.requestRender();
        });
    };
    window.addEventListener('resize', onResize, { passive: true });

    let paused = false;
    const onVisibility = () => {
        if (document.hidden) loop.stop();
        else if (!paused) loop.start();
    };
    document.addEventListener('visibilitychange', onVisibility);

    const canvas = renderer.domElement;
    const onContextLost = (event) => {
        event.preventDefault();
        loop.stop();
    };
    const onContextRestored = () => {
        if (!paused) (loop.isStatic ? loop.requestRender() : loop.start());
    };
    canvas.addEventListener('webglcontextlost', onContextLost);
    canvas.addEventListener('webglcontextrestored', onContextRestored);

    // Arranque
    if (prefersReducedMotion()) {
        loop.setStatic(true);
    } else {
        loop.start();
    }

    /*
    | Controlador público
    */
    const controller = {
        mode: opts.mode,

        get data() {
            return data;
        },

        setData,

        setStationStatus(id, status) {
            const current = data ?? { stations: [] };
            const others = (current.stations ?? []).filter((s) => s.id !== id);
            setData({ ...current, stations: [...others, { id, status }] });
        },

        setTheme(theme) {
            themeSetting = theme;
            applyTheme();
        },

        pause() {
            paused = true;
            loop.stop();
        },

        resume() {
            paused = false;
            if (loop.isStatic) loop.requestRender();
            else if (!document.hidden) loop.start();
        },

        destroy() {
            loop.stop();
            interactions?.dispose();
            labels?.dispose();
            themeObserver.disconnect();
            motionQuery.removeEventListener('change', onMotionChange);
            window.removeEventListener('resize', onResize);
            window.removeEventListener('scroll', onScroll);
            document.removeEventListener('visibilitychange', onVisibility);
            canvas.removeEventListener('webglcontextlost', onContextLost);
            canvas.removeEventListener('webglcontextrestored', onContextRestored);

            scene.traverse((obj) => {
                if (obj.isInstancedMesh) obj.dispose();
                if (obj.isLineSegments) obj.geometry.dispose();
            });
            stations.forEach((s) => {
                s.beaconMaterial.dispose();
                s.haloMaterial.dispose();
            });
            derivedBelts.forEach((mat) => {
                mat.map?.dispose();
                mat.dispose();
            });
            disposeAll(geometries, materials);
            renderer.dispose();
            canvas.remove();
            container.classList.remove('is-ready');
        },
    };

    return controller;
}
