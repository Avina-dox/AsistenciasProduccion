import * as THREE from 'three';

/*
|--------------------------------------------------------------------------
| Loop de animación y cámara automática
|--------------------------------------------------------------------------
| - Limita FPS (es un fondo: no necesita más).
| - Se pausa con la pestaña oculta.
| - Modo estático (prefers-reduced-motion): no hay loop, sólo se renderiza
|   bajo demanda (resize, cambio de tema, hover).
| - Calidad adaptativa: si los frames llegan tarde de forma sostenida,
|   baja el pixel ratio y después el límite de FPS.
*/

export function createLoop({ maxFps, onFrame, onSlow }) {
    let rafId = null;
    let running = false;
    let staticMode = false;
    let lastTime = 0;
    let elapsed = 0;
    let fps = maxFps;

    // Estadística para calidad adaptativa
    let slowFrames = 0;
    let sampledFrames = 0;

    const tick = (now) => {
        rafId = requestAnimationFrame(tick);

        // Primer tick: dibuja ya y toma el reloj de rAF como referencia
        // (su timestamp puede ir "atrás" de performance.now()).
        if (lastTime < 0) {
            lastTime = now;
            onFrame(elapsed, 0, false);

            return;
        }

        const interval = 1000 / fps;
        const delta = now - lastTime;

        // Tolerancia de 1ms para no saltar frames por jitter del rAF.
        if (delta < interval - 1) return;

        lastTime = now;

        // Tras una pausa larga no "saltamos" la animación.
        const dt = Math.min(delta / 1000, 0.1);
        elapsed += dt;

        sampledFrames++;
        if (delta > interval * 1.6) slowFrames++;

        if (sampledFrames >= 120) {
            if (slowFrames / sampledFrames > 0.5) {
                const handled = onSlow?.();
                if (!handled && fps > 30) fps = 30;
            }
            slowFrames = 0;
            sampledFrames = 0;
        }

        onFrame(elapsed, dt, false);
    };

    function start() {
        if (running || staticMode) return;
        running = true;
        lastTime = -1;
        slowFrames = 0;
        sampledFrames = 0;
        rafId = requestAnimationFrame(tick);
    }

    function stop() {
        running = false;
        if (rafId !== null) cancelAnimationFrame(rafId);
        rafId = null;
    }

    let pendingStatic = null;

    function requestRender() {
        if (running || pendingStatic !== null) return;

        pendingStatic = requestAnimationFrame(() => {
            pendingStatic = null;
            onFrame(elapsed, 0, true);
        });
    }

    function setStatic(value) {
        staticMode = value;

        if (value) {
            stop();
            requestRender();
        } else {
            start();
        }
    }

    return {
        start,
        stop,
        setStatic,
        requestRender,
        get isStatic() {
            return staticMode;
        },
        get isRunning() {
            return running;
        },
    };
}

/*
| Cámara con deriva lenta y orgánica (suma de senos de baja frecuencia,
| con periodos distintos para que nunca se sienta un "loop").
| `scroll` (0..1) desplaza la cámara a lo largo de la línea en dashboard.
*/
export function createCameraRig(camera, cameraConfig) {
    const basePosition = new THREE.Vector3(...cameraConfig.position);
    const baseTarget = new THREE.Vector3(...cameraConfig.target);
    const target = new THREE.Vector3();
    let scroll = 0;

    function update(t, { amplitude, scrollTarget = 0, instant = false, dt = 0 }) {
        const blend = instant ? 1 : 1 - Math.exp(-dt * 2.5);
        scroll += (scrollTarget - scroll) * blend;

        const a = amplitude;

        camera.position.set(
            basePosition.x + Math.sin(t * 0.07) * 0.9 * a + scroll * 2.6,
            basePosition.y + Math.sin(t * 0.05 + 1.3) * 0.28 * a - scroll * 0.9,
            basePosition.z + Math.sin(t * 0.043 + 2.1) * 0.5 * a,
        );

        target.set(
            baseTarget.x + Math.sin(t * 0.06 + 0.4) * 0.35 * a + scroll * 2.6,
            baseTarget.y + Math.sin(t * 0.08 + 2.7) * 0.1 * a,
            baseTarget.z,
        );

        camera.lookAt(target);
    }

    function setConfig(config) {
        basePosition.set(...config.position);
        baseTarget.set(...config.target);
    }

    return { update, setConfig };
}
