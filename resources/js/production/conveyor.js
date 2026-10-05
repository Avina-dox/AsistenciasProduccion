import * as THREE from 'three';
import { instanced, part } from './materials.js';

/*
|--------------------------------------------------------------------------
| Banda transportadora
|--------------------------------------------------------------------------
| Banda (textura con desplazamiento), rieles, tambores en los extremos,
| rodillos de retorno visibles bajo la banda y patas instanciadas.
| El movimiento es puramente visual: offset de textura + giro de rodillos.
*/

const STRIPES_PER_UNIT = 3;

export function createConveyor({
    geometries: g,
    materials: m,
    beltMaterial,
    x0,
    x1,
    z = 0,
    height = 0.95,
    width = 1.1,
    direction = 1,
    shadows = true,
}) {
    const group = new THREE.Group();
    const length = x1 - x0;
    const cx = (x0 + x1) / 2;

    beltMaterial.map.repeat.set(length * STRIPES_PER_UNIT, 1);

    // Banda
    group.add(part(g.box, beltMaterial, [length, 0.06, width], [cx, height, z], { shadows }));

    // Rieles laterales
    for (const side of [-1, 1]) {
        group.add(part(g.box, m.metal, [length, 0.16, 0.07], [cx, height - 0.02, z + side * (width / 2 + 0.05)], { shadows }));
        // Larguero inferior
        group.add(part(g.box, m.metalDark, [length, 0.08, 0.06], [cx, height - 0.42, z + side * (width / 2 + 0.02)], { shadows: false }));
    }

    // Rodillos de retorno (visibles bajo la banda) y tambores extremos
    const spinners = [];

    const addSpinner = (x, y, radius) => {
        const roller = part(g.roller, m.rubber, [radius * 2, width - 0.04, radius * 2], [x, y, z], { shadows: false });
        roller.rotation.x = Math.PI / 2;
        roller.userData.radius = radius;
        group.add(roller);
        spinners.push(roller);
    };

    for (let x = x0 + 0.9; x < x1 - 0.6; x += 1.5) {
        addSpinner(x, height - 0.25, 0.07);
    }

    addSpinner(x0, height - 0.08, 0.13);
    addSpinner(x1, height - 0.08, 0.13);

    // Patas, travesaños y pies (instanciados: no se mueven)
    const legs = [];
    const braces = [];
    const legHeight = height - 0.08;

    for (let x = x0 + 0.4; x <= x1 - 0.3; x += 2.6) {
        for (const side of [-1, 1]) {
            legs.push({ position: [x, legHeight / 2, z + side * (width / 2)], scale: [0.08, legHeight, 0.08] });
        }
        braces.push({ position: [x, 0.25, z], scale: [0.06, 0.06, width] });
    }

    group.add(instanced(g.box, m.metalDark, legs, { shadows }));
    group.add(instanced(g.box, m.metalDark, braces, { shadows: false }));

    // Motor de arrastre en el extremo de salida
    const driveX = direction > 0 ? x1 - 0.35 : x0 + 0.35;
    group.add(part(g.box, m.metalDark, [0.5, 0.36, 0.34], [driveX, height - 0.38, z + width / 2 + 0.28], { shadows }));
    group.add(part(g.box, m.accent, [0.52, 0.06, 0.36], [driveX, height - 0.18, z + width / 2 + 0.28], { shadows: false }));

    const map = beltMaterial.map;

    function update(dt, speed) {
        if (speed === 0 || dt === 0) return;

        const travel = speed * dt * direction;

        map.offset.x = (map.offset.x - travel * STRIPES_PER_UNIT) % 1;

        for (const roller of spinners) {
            roller.rotation.y -= travel / roller.userData.radius;
        }
    }

    return { group, update, length };
}
