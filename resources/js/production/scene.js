import * as THREE from 'three';

/*
|--------------------------------------------------------------------------
| Renderer, cámara, niebla e iluminación
|--------------------------------------------------------------------------
| Iluminación deliberadamente mínima: hemisférica (relleno), una
| direccional principal con sombras y una direccional de contraluz que
| perfila las máquinas. Los indicadores usan materiales emisivos, no luces.
| Hay una sola PointLight para el hover, siempre presente (intensidad 0)
| para no recompilar shaders al activarla.
*/

export function createRenderer(container, opts) {
    const renderer = new THREE.WebGLRenderer({
        antialias: opts.antialias,
        alpha: false,
        powerPreference: 'low-power',
        stencil: false,
    });

    renderer.setPixelRatio(Math.min(window.devicePixelRatio, opts.maxPixelRatio));
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1;
    renderer.shadowMap.enabled = opts.shadows;
    renderer.shadowMap.type = THREE.PCFShadowMap;

    const canvas = renderer.domElement;
    canvas.setAttribute('aria-hidden', 'true');
    canvas.setAttribute('tabindex', '-1');
    canvas.classList.add('production-bg__canvas');
    container.appendChild(canvas);

    return renderer;
}

export function createSceneContext(container, opts, palette) {
    const renderer = createRenderer(container, opts);
    const scene = new THREE.Scene();

    scene.background = new THREE.Color();
    // Niebla relativa a la distancia de cámara: la línea queda nítida y el fondo se disuelve.
    const cameraDistance = new THREE.Vector3(...opts.camera.position).distanceTo(new THREE.Vector3(...opts.camera.target));
    scene.fog = new THREE.Fog(0x000000, cameraDistance * 1.05, cameraDistance * 2.7);

    const camera = new THREE.PerspectiveCamera(opts.camera.fov, 1, 0.5, 120);

    // Luces
    const hemi = new THREE.HemisphereLight();
    scene.add(hemi);

    const key = new THREE.DirectionalLight();
    key.position.set(-10, 18, 12);
    key.target.position.set(0, 0, -2);
    scene.add(key, key.target);

    if (opts.shadows) {
        key.castShadow = true;
        key.shadow.mapSize.set(1024, 1024);
        key.shadow.camera.left = -22;
        key.shadow.camera.right = 22;
        key.shadow.camera.top = 14;
        key.shadow.camera.bottom = -14;
        key.shadow.camera.near = 1;
        key.shadow.camera.far = 60;
        key.shadow.bias = -0.0008;
        key.shadow.normalBias = 0.02;
        key.shadow.radius = 3;
    }

    const rim = new THREE.DirectionalLight();
    rim.position.set(8, 9, -16);
    scene.add(rim);

    const hoverLight = new THREE.PointLight(0xffffff, 0, 7, 2);
    hoverLight.position.set(0, 3, 1.5);
    scene.add(hoverLight);

    const context = {
        renderer,
        scene,
        camera,
        lights: { hemi, key, rim, hoverLight },
        width: 1,
        height: 1,
    };

    applyScenePalette(context, palette);
    resizeContext(context, container, opts);

    return context;
}

export function applyScenePalette({ scene, lights }, palette) {
    scene.background.set(palette.background);
    scene.fog.color.set(palette.background);

    lights.hemi.color.set(palette.hemiSky);
    lights.hemi.groundColor.set(palette.hemiGround);
    lights.hemi.intensity = palette.hemiIntensity;

    lights.key.color.set(palette.keyColor);
    lights.key.intensity = palette.keyIntensity;

    lights.rim.color.set(palette.rimColor);
    lights.rim.intensity = palette.rimIntensity;
}

/*
| Ajusta tamaño, FOV y encuadre. En pantallas angostas abre el FOV para
| que se vea al menos `fitWidth` unidades de planta; `offsetX/offsetY`
| desplazan el render (p. ej. planta a la izquierda en login).
*/
export function resizeContext(context, container, opts) {
    const width = Math.max(container.clientWidth, 1);
    const height = Math.max(container.clientHeight, 1);
    const { camera, renderer } = context;
    const cam = opts.camera;

    context.width = width;
    context.height = height;

    const aspect = width / height;
    const distance = Math.hypot(
        cam.position[0] - cam.target[0],
        cam.position[1] - cam.target[1],
        cam.position[2] - cam.target[2],
    );

    const fitFov = THREE.MathUtils.radToDeg(2 * Math.atan(cam.fitWidth / 2 / (distance * aspect)));

    camera.aspect = aspect;
    camera.fov = THREE.MathUtils.clamp(Math.max(cam.fov, fitFov), cam.fov, 62);

    if (cam.offsetX || cam.offsetY) {
        camera.setViewOffset(width, height, width * cam.offsetX, height * cam.offsetY, width, height);
    } else {
        camera.clearViewOffset();
    }

    camera.updateProjectionMatrix();
    renderer.setSize(width, height, false);
}
