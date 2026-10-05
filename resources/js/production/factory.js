import * as THREE from 'three';
import { instanced, part } from './materials.js';

/*
|--------------------------------------------------------------------------
| Planta: piso, rejilla, zonas, columnas, silos, racks y tuberías
|--------------------------------------------------------------------------
| Todo lo de este módulo es estático: al final se "congelan" las matrices
| (matrixAutoUpdate = false) para que el render loop no las recalcule.
|
| Capas de profundidad (eje Z, positivo hacia la cámara):
|   +4.5   bolardos (primer plano)
|    0     línea principal
|   -7     línea secundaria (dashboard)
|  -12     columnas estructurales
|  -17/-19 racks de almacén y silos
|  -26     muro de fondo
*/

// Pseudoaleatorio determinista: la planta se ve igual en cada carga.
function seeded(seed) {
    let s = seed;

    return () => {
        s = (s * 16807) % 2147483647;

        return (s - 1) / 2147483646;
    };
}

function createGrid(material, size = 120, step = 2) {
    const positions = [];
    const half = size / 2;

    for (let v = -half; v <= half; v += step) {
        positions.push(-half, 0, v, half, 0, v);
        positions.push(v, 0, -half, v, 0, half);
    }

    const geometry = new THREE.BufferGeometry();
    geometry.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));

    const grid = new THREE.LineSegments(geometry, material);
    grid.position.y = 0.004;

    return grid;
}

function createSilos(g, m, count, shadows) {
    const group = new THREE.Group();
    const legs = [];

    for (let i = 0; i < count; i++) {
        const x = -24 + i * 3.8;
        const z = -19 - (i % 2) * 1.2;

        for (const [dx, dz] of [[-1.1, -1.1], [1.1, -1.1], [-1.1, 1.1], [1.1, 1.1]]) {
            legs.push({ position: [x + dx, 1.1, z + dz], scale: [0.16, 2.2, 0.16] });
        }

        group.add(part(g.cylinder, m.silo, [3.2, 7, 3.2], [x, 5.6, z], { shadows }));
        group.add(part(g.roof, m.silo, [3.2, 1.1, 3.2], [x, 9.65, z], { shadows }));
        group.add(part(g.cone, m.silo, [3.2, 1.6, 3.2], [x, 1.3, z], { shadows }));
        group.add(part(g.cylinder, m.metalDark, [3.3, 0.12, 3.3], [x, 4, z], { shadows: false }));
        group.add(part(g.cylinder, m.metalDark, [3.3, 0.12, 3.3], [x, 7.2, z], { shadows: false }));
    }

    group.add(instanced(g.box, m.metalDark, legs, { shadows }));

    // Pasarela superior que une los silos.
    const width = (count - 1) * 3.8 + 2;
    group.add(part(g.box, m.metalDark, [width, 0.12, 1], [-24 + ((count - 1) * 3.8) / 2, 10.3, -19.6], { shadows: false }));

    return group;
}

/*
| Tubería que lleva la materia prima del silo hasta la tolva de preparación.
*/
function createFeedPipe(g, m, mixerX) {
    const group = new THREE.Group();
    const d = 0.3; // diámetro
    const siloX = -20.2; // segundo silo
    const siloZ = -20.2;
    const runY = 9; // altura del tramo horizontal
    const pipeX = mixerX - 0.8; // a un lado del motor del mezclador
    const dropTo = 4.5; // tapa del mezclador

    // Sale del costado del silo a la altura del tramo
    const fromSilo = part(g.cylinder, m.pipe, [d, pipeX - siloX, d], [(siloX + pipeX) / 2, runY, siloZ], { shadows: false });
    fromSilo.rotation.z = Math.PI / 2;
    group.add(fromSilo);

    // Viene hacia la cámara (eje Z) hasta encima del mezclador
    const alongZ = part(g.cylinder, m.pipe, [d, -siloZ, d], [pipeX, runY, siloZ / 2], { shadows: false });
    alongZ.rotation.x = Math.PI / 2;
    group.add(alongZ);

    // Baja hasta la tapa del mezclador
    group.add(part(g.cylinder, m.pipe, [d, runY - dropTo, d], [pipeX, (runY + dropTo) / 2, 0], { shadows: false }));

    // Codos
    group.add(part(g.sphere, m.pipe, [d * 1.15, d * 1.15, d * 1.15], [pipeX, runY, siloZ], { shadows: false }));
    group.add(part(g.sphere, m.pipe, [d * 1.15, d * 1.15, d * 1.15], [pipeX, runY, 0], { shadows: false }));

    return group;
}

function createRacks(g, m, bays, shadows) {
    const rand = seeded(42);
    const uprights = [];
    const beams = [];
    const loads = [];
    const pallets = [];

    const bayWidth = 3.1;
    const depth = 1.3;
    const levels = [0.15, 1.75, 3.35, 4.95];
    const startX = 3;
    const z = -17;

    for (let b = 0; b <= bays; b++) {
        const x = startX + b * bayWidth;

        for (const dz of [-depth / 2, depth / 2]) {
            uprights.push({ position: [x, 3.1, z + dz], scale: [0.12, 6.2, 0.12] });
        }
    }

    for (let b = 0; b < bays; b++) {
        const cx = startX + b * bayWidth + bayWidth / 2;

        for (const level of levels) {
            if (level > 0.5) {
                for (const dz of [-depth / 2, depth / 2]) {
                    beams.push({ position: [cx, level - 0.1, z + dz], scale: [bayWidth, 0.14, 0.08] });
                }
            }

            for (const dx of [-0.72, 0.72]) {
                if (rand() < 0.22) continue;

                const h = 0.75 + rand() * 0.35;
                pallets.push({ position: [cx + dx, level + 0.07, z], scale: [1.2, 0.14, 1.1] });
                loads.push({ position: [cx + dx, level + 0.14 + h / 2, z], scale: [1.1, h, 1.0] });
            }
        }
    }

    const group = new THREE.Group();
    group.add(instanced(g.box, m.rack, uprights, { shadows }));
    group.add(instanced(g.box, m.rackBeam, beams, { shadows }));
    group.add(instanced(g.box, m.pallet, pallets, { shadows }));
    group.add(instanced(g.box, m.load, loads, { shadows }));

    return group;
}

function createPipes(g, m, count) {
    const group = new THREE.Group();

    for (let i = 0; i < count; i++) {
        const pipe = part(g.cylinder, m.pipe, [0.28 - i * 0.04, 60, 0.28 - i * 0.04], [0, 8.2 + i * 0.45, -5 - i * 1.3], { shadows: false });
        pipe.rotation.z = Math.PI / 2;
        group.add(pipe);
    }

    // Soportes colgantes de las tuberías
    const hangers = [];
    for (let x = -24; x <= 24; x += 8) {
        hangers.push({ position: [x, 9.6, -5 - ((count - 1) * 1.3) / 2], scale: [0.08, 0.08, (count - 1) * 1.3 + 0.8] });
    }
    group.add(instanced(g.box, m.metalDark, hangers, { shadows: false }));

    return group;
}

export function createFactory({ geometries: g, materials: m, density, shadows, mixerX }) {
    const group = new THREE.Group();
    group.name = 'factory';

    // Piso
    const floor = part(g.box, m.floor, [180, 0.02, 180], [0, -0.01, 0], { shadows: false });
    floor.receiveShadow = shadows;
    group.add(floor);

    group.add(createGrid(m.grid));

    // Zona de la línea principal (epóxico) y franjas de seguridad
    const pad = part(g.box, m.floorPad, [38, 0.02, 6.4], [0, 0.01, 0], { shadows: false });
    pad.receiveShadow = shadows;
    group.add(pad);

    const stripes = [
        { position: [0, 0.022, 3.2], scale: [38, 0.012, 0.09] },
        { position: [0, 0.022, -3.2], scale: [38, 0.012, 0.09] },
    ];

    if (density.secondaryLine) {
        const pad2 = part(g.box, m.floorPad, [32, 0.02, 3], [3, 0.01, -7], { shadows: false });
        pad2.receiveShadow = shadows;
        group.add(pad2);
        stripes.push(
            { position: [3, 0.022, -5.5], scale: [32, 0.012, 0.07] },
            { position: [3, 0.022, -8.5], scale: [32, 0.012, 0.07] },
        );
    }

    group.add(instanced(g.box, m.safety, stripes, { shadows: false }));

    // Bolardos en primer plano: dan profundidad sin tapar la línea.
    const bollards = [];
    const bollardTops = [];
    for (let x = -18; x <= 18; x += 6) {
        bollards.push({ position: [x, 0.45, 4.6], scale: [0.22, 0.9, 0.22] });
        bollardTops.push({ position: [x, 0.95, 4.6], scale: [0.24, 0.12, 0.24] });
    }
    group.add(instanced(g.cylinder, m.metalDark, bollards, { shadows }));
    group.add(instanced(g.cylinder, m.safety, bollardTops, { shadows: false }));

    // Columnas estructurales
    const pillars = [];
    const spread = 48 / Math.max(density.pillars - 1, 1);
    for (let i = 0; i < density.pillars; i++) {
        pillars.push({ position: [-24 + i * spread, 6, -12], scale: [0.6, 12, 0.6] });
    }
    group.add(instanced(g.box, m.metalDark, pillars, { shadows }));

    // Fondo: silos, almacén, tuberías y muro
    group.add(createSilos(g, m, density.silos, shadows));
    group.add(createFeedPipe(g, m, mixerX));
    group.add(createRacks(g, m, density.racks, shadows));
    group.add(createPipes(g, m, density.pipes));

    group.add(part(g.box, m.wall, [160, 22, 0.3], [0, 11, -26], { shadows: false }));

    // Línea de luz tenue en el muro (acento "gemelo digital")
    group.add(part(g.box, m.glow, [160, 0.05, 0.05], [0, 6.5, -25.8], { shadows: false }));

    // Todo es estático: congelamos matrices.
    group.updateMatrixWorld(true);
    group.traverse((obj) => {
        obj.matrixAutoUpdate = false;
    });

    return { group };
}
