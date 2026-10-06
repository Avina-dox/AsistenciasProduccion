import * as THREE from 'three';
import { mergeGeometries } from 'three/addons/utils/BufferGeometryUtils.js';

/*
|--------------------------------------------------------------------------
| Geometrías y materiales compartidos
|--------------------------------------------------------------------------
| Toda la planta se arma con un puñado de geometrías unitarias escaladas y
| materiales compartidos por "rol" (metal, carcasa, banda...). Cambiar de
| tema sólo recolorea estos materiales: no se crea nada nuevo.
*/

export function createGeometries(isMobile) {
    const radial = isMobile ? 12 : 20;

    return {
        box: new THREE.BoxGeometry(1, 1, 1),
        cylinder: new THREE.CylinderGeometry(0.5, 0.5, 1, radial),
        // Pocas caras + flatShading: así se percibe el giro de rodillos y tambores.
        roller: new THREE.CylinderGeometry(0.5, 0.5, 1, 10),
        cone: new THREE.CylinderGeometry(0.5, 0.12, 1, radial),
        roof: new THREE.CylinderGeometry(0.08, 0.5, 1, radial),
        sphere: new THREE.SphereGeometry(0.5, 16, 12),
        // Producto: fila de 4 barritas de granola atravesadas en la banda.
        // Alto unitario (se escala por etapa); largo y ancho reales.
        bars: createBarsGeometry(),
        halo: new THREE.RingGeometry(0.82, 1, 48),
    };
}

/*
| 4 barritas (0.44 × 0.11 m) separadas 3 cm, unidas en una sola geometría
| para que cada etapa sea un único InstancedMesh.
*/
function createBarsGeometry() {
    const bars = [-0.21, -0.07, 0.07, 0.21].map((z) => {
        const bar = new THREE.BoxGeometry(0.44, 1, 0.11);
        bar.translate(0, 0, z);

        return bar;
    });

    const merged = mergeGeometries(bars);
    bars.forEach((bar) => bar.dispose());

    return merged;
}

/*
| Textura de granola: copos de avena y motas sobre base clara. Es neutra
| (casi blanca) y se tiñe con el color del material: crudo o horneado.
*/
function createGranolaTexture() {
    const canvas = document.createElement('canvas');
    canvas.width = 128;
    canvas.height = 64;

    const ctx = canvas.getContext('2d');
    ctx.fillStyle = '#f2ede4';
    ctx.fillRect(0, 0, 128, 64);

    // Pseudoaleatorio determinista: misma textura en cada carga.
    let seed = 11;
    const rand = () => {
        seed = (seed * 16807) % 2147483647;

        return (seed - 1) / 2147483646;
    };

    const flakes = ['#fffaf0', '#d8cbb4', '#c9b89a', '#e9dfcc'];
    for (let i = 0; i < 220; i++) {
        ctx.fillStyle = flakes[i % flakes.length];
        ctx.beginPath();
        ctx.ellipse(rand() * 128, rand() * 64, 1.5 + rand() * 3, 1 + rand() * 1.8, rand() * Math.PI, 0, Math.PI * 2);
        ctx.fill();
    }

    // Motas oscuras (semillas / pasas)
    ctx.fillStyle = '#8a7560';
    for (let i = 0; i < 28; i++) {
        ctx.beginPath();
        ctx.arc(rand() * 128, rand() * 64, 0.6 + rand() * 1.1, 0, Math.PI * 2);
        ctx.fill();
    }

    const texture = new THREE.CanvasTexture(canvas);
    texture.colorSpace = THREE.SRGBColorSpace;
    texture.wrapS = THREE.RepeatWrapping;
    texture.wrapT = THREE.RepeatWrapping;

    return texture;
}

function standard(params) {
    return new THREE.MeshStandardMaterial({ roughness: 0.65, metalness: 0.1, ...params });
}

/*
| Textura de la banda: franjas transversales muy tenues. Desplazar su
| offset da la sensación de movimiento sin mover geometría.
*/
function createBeltTexture() {
    const canvas = document.createElement('canvas');
    canvas.width = 32;
    canvas.height = 8;

    const ctx = canvas.getContext('2d');
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, 32, 8);
    ctx.fillStyle = '#b9b9b9';
    ctx.fillRect(0, 0, 3, 8);

    const texture = new THREE.CanvasTexture(canvas);
    texture.wrapS = THREE.RepeatWrapping;
    texture.wrapT = THREE.RepeatWrapping;
    texture.colorSpace = THREE.SRGBColorSpace;

    return texture;
}

/*
| Cara frontal de las cajas: color de cartón + logo circular. El canvas es
| cuadrado (potencia de 2) pero la cara es más ancha que alta, así que el
| logo se dibuja "comprimido" en X para que en la caja se vea redondo.
*/
const BOX_FACE_ASPECT = 0.62 / 0.42;

function createBoxFaceTexture() {
    const canvas = document.createElement('canvas');
    canvas.width = 512;
    canvas.height = 512;

    const texture = new THREE.CanvasTexture(canvas);
    texture.colorSpace = THREE.SRGBColorSpace;

    let logo = null;

    function draw(boxColor) {
        const ctx = canvas.getContext('2d');
        const size = canvas.width;

        ctx.fillStyle = boxColor;
        ctx.fillRect(0, 0, size, size);

        if (logo) {
            const h = size * 0.78;
            const w = h / BOX_FACE_ASPECT;
            const x = (size - w) / 2;
            const y = (size - h) / 2;

            // Disco claro detrás del logo para que su borde morado contraste con el cartón.
            ctx.fillStyle = '#ffffff';
            ctx.beginPath();
            ctx.ellipse(size / 2, size / 2, w / 2 + 10 / BOX_FACE_ASPECT, h / 2 + 10, 0, 0, Math.PI * 2);
            ctx.fill();

            ctx.imageSmoothingQuality = 'high';
            ctx.drawImage(logo, x, y, w, h);
        }

        texture.needsUpdate = true;
    }

    return {
        texture,
        draw,
        setLogo(image, boxColor) {
            logo = image;
            draw(boxColor);
        },
    };
}

/*
| Carga el logo y lo pinta en las cajas. Si falla, las cajas quedan lisas.
*/
export function loadBoxLogo(materials, url, palette, onLoad) {
    if (!url) return;

    const image = new Image();
    image.decoding = 'async';
    image.onload = () => {
        materials.boxLogo.userData.face.setLogo(image, palette().box);
        onLoad?.();
    };
    image.src = url;
}

/*
| Materiales por cara para BoxGeometry (+x, -x, +y, -y, +z, -z):
| logo al frente y atrás, cartón liso en el resto.
*/
export function boxFaceMaterials(m) {
    return [m.box, m.box, m.box, m.box, m.boxLogo, m.boxLogo];
}

export function createMaterials(palette) {
    const boxFace = createBoxFaceTexture();
    const granola = createGranolaTexture();

    const m = {
        floor: standard({ roughness: 0.92 }),
        floorPad: standard({ roughness: 0.85 }),
        safety: standard({ roughness: 0.7 }),
        metal: standard({ roughness: 0.38, metalness: 0.35 }),
        metalDark: standard({ roughness: 0.55, metalness: 0.25 }),
        body: standard({ roughness: 0.5, metalness: 0.15 }),
        panel: standard({ roughness: 0.6 }),
        accent: standard({ roughness: 0.45 }),
        glow: standard({ roughness: 0.4, emissiveIntensity: 1.1 }),
        heat: standard({ roughness: 0.4, emissiveIntensity: 1.2 }),
        // Barritas de granola: misma textura, distinto tono (cruda / horneada).
        raw: standard({ roughness: 0.9, map: granola }),
        baked: standard({ roughness: 0.75, map: granola }),
        box: standard({ roughness: 0.75 }),
        boxLogo: standard({ roughness: 0.7, map: boxFace.texture }),
        tape: standard({ roughness: 0.6 }),
        pallet: standard({ roughness: 0.9 }),
        rack: standard({ roughness: 0.6, metalness: 0.2 }),
        rackBeam: standard({ roughness: 0.6 }),
        load: standard({ roughness: 0.8 }),
        silo: standard({ roughness: 0.4, metalness: 0.3 }),
        wall: standard({ roughness: 0.95 }),
        pipe: standard({ roughness: 0.45, metalness: 0.3 }),
        belt: standard({ roughness: 0.8, map: createBeltTexture() }),
        rubber: standard({ roughness: 0.75, flatShading: true }),
        grid: new THREE.LineBasicMaterial({ transparent: true, depthWrite: false }),
    };

    m.boxLogo.userData.face = boxFace;

    applyPalette(m, palette);

    return m;
}

/*
| Recolorea los materiales según la paleta activa.
*/
export function applyPalette(m, palette) {
    const roles = [
        'floor', 'floorPad', 'safety', 'metal', 'metalDark', 'body', 'panel', 'accent',
        'raw', 'baked', 'box', 'tape', 'pallet', 'rack', 'rackBeam', 'load', 'silo',
        'wall', 'pipe', 'belt', 'rubber',
    ];

    for (const role of roles) {
        m[role].color.set(palette[role]);
    }

    // El color del cartón va pintado en la textura (el material queda en blanco).
    m.boxLogo.userData.face.draw(palette.box);

    m.glow.color.set(palette.glow);
    m.glow.emissive.set(palette.glow);
    m.heat.color.set(palette.heat);
    m.heat.emissive.set(palette.heat);

    m.grid.color.set(palette.grid);
    m.grid.opacity = palette.gridOpacity;
}

/*
| Crea un Mesh con una geometría compartida, escalado y posicionado.
*/
export function part(geometry, material, scale, position, { shadows = true } = {}) {
    const mesh = new THREE.Mesh(geometry, material);

    mesh.scale.set(scale[0], scale[1], scale[2]);
    mesh.position.set(position[0], position[1], position[2]);
    mesh.castShadow = shadows;
    mesh.receiveShadow = shadows;

    return mesh;
}

/*
| InstancedMesh a partir de una lista de transformaciones estáticas
| [{ position, scale, rotationY? }]. Ideal para patas, racks, columnas...
*/
export function instanced(geometry, material, items, { shadows = true } = {}) {
    const mesh = new THREE.InstancedMesh(geometry, material, items.length);
    const dummy = new THREE.Object3D();

    items.forEach((item, i) => {
        dummy.position.set(item.position[0], item.position[1], item.position[2]);
        dummy.scale.set(item.scale[0], item.scale[1], item.scale[2]);
        dummy.rotation.set(0, item.rotationY ?? 0, 0);
        dummy.updateMatrix();
        mesh.setMatrixAt(i, dummy.matrix);
    });

    mesh.instanceMatrix.needsUpdate = true;
    mesh.computeBoundingSphere();
    mesh.castShadow = shadows;
    mesh.receiveShadow = shadows;

    return mesh;
}

export function disposeAll(geometries, materials) {
    Object.values(geometries).forEach((g) => g.dispose());
    Object.values(materials).forEach((mat) => {
        mat.map?.dispose();
        mat.dispose();
    });
}
