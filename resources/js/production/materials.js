import * as THREE from 'three';

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
        // Porción de producto crudo / horneado (disco con borde redondeado).
        portion: new THREE.CylinderGeometry(0.5, 0.46, 1, 14),
        halo: new THREE.RingGeometry(0.82, 1, 48),
    };
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

export function createMaterials(palette) {
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
        raw: standard({ roughness: 0.85 }),
        baked: standard({ roughness: 0.7 }),
        box: standard({ roughness: 0.75 }),
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
