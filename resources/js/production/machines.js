import * as THREE from 'three';
import { STATUS_COLORS } from './config.js';
import { part } from './materials.js';

/*
|--------------------------------------------------------------------------
| Estaciones de la línea
|--------------------------------------------------------------------------
| Cada estación es un THREE.Group con:
|   userData.id / label / status   → identidad y estado (running, warning,
|                                    stopped, maintenance)
|   beacon                         → torreta de estado (material propio)
|   halo                           → anillo en el piso (hover / estado)
|   hitbox                         → volumen invisible para el raycast
|   animate(t, dt, ctx)            → movimientos mecánicos propios
*/

const VALID_STATUS = Object.keys(STATUS_COLORS);

export function normalizeStatus(status) {
    return VALID_STATUS.includes(status) ? status : 'running';
}

function isActive(status) {
    return status === 'running' || status === 'warning';
}

/*
| Base común: torreta de estado, halo en piso, hitbox y panel de control.
*/
function createStationBase(def, { geometries: g, materials: m, shadows }, layout) {
    const group = new THREE.Group();
    group.position.set(def.x, 0, 0);
    group.name = `station:${def.id}`;

    // Contenedor que escala en hover (la torreta y el halo quedan fuera para no "saltar").
    const body = new THREE.Group();
    group.add(body);

    const beaconMaterial = new THREE.MeshStandardMaterial({ roughness: 0.3, emissiveIntensity: 1.4 });
    const haloMaterial = new THREE.MeshBasicMaterial({
        transparent: true,
        opacity: 0,
        depthWrite: false,
        side: THREE.DoubleSide,
    });

    // Torreta de estado detrás de la línea
    const [bx, bz] = layout.beacon;
    group.add(part(g.cylinder, m.metalDark, [0.07, layout.beaconHeight, 0.07], [bx, layout.beaconHeight / 2, bz], { shadows: false }));
    group.add(part(g.cylinder, m.metalDark, [0.3, 0.06, 0.3], [bx, 0.03, bz], { shadows: false }));

    const beacon = part(g.sphere, beaconMaterial, [0.26, 0.26, 0.26], [bx, layout.beaconHeight + 0.1, bz], { shadows: false });
    group.add(beacon);

    // Halo en el piso
    const halo = new THREE.Mesh(g.halo, haloMaterial);
    halo.rotation.x = -Math.PI / 2;
    halo.position.set(layout.hitbox.center[0], 0.035, 0);
    halo.scale.setScalar(layout.haloRadius);
    group.add(halo);

    // Volumen de interacción
    const hitbox = new THREE.Mesh(g.box, beaconMaterial);
    hitbox.visible = false;
    hitbox.scale.set(...layout.hitbox.size);
    hitbox.position.set(...layout.hitbox.center);
    hitbox.userData.stationId = def.id;
    group.add(hitbox);

    // Panel de control frontal con pantalla
    const [px, pz] = layout.panel;
    body.add(part(g.box, m.metalDark, [0.08, 1.1, 0.08], [px, 0.55, pz], { shadows }));
    body.add(part(g.box, m.panel, [0.55, 0.42, 0.12], [px, 1.25, pz], { shadows }));
    body.add(part(g.box, m.glow, [0.4, 0.24, 0.02], [px, 1.27, pz + 0.07], { shadows: false }));

    group.userData = {
        id: def.id,
        label: def.label,
        type: def.type,
        status: 'running',
    };

    return {
        id: def.id,
        label: def.label,
        group,
        body,
        beacon,
        beaconMaterial,
        halo,
        haloMaterial,
        hitbox,
        anchor: new THREE.Vector3(def.x + layout.anchor[0], layout.anchor[1], layout.anchor[2]),
        hover: 0,
        hoverTarget: 0,
        status: 'running',
    };
}

/*
| 1. Preparación: tanque mezclador con tolva sobre el inicio de la banda.
*/
function buildMixer(def, ctx) {
    const { geometries: g, materials: m, shadows } = ctx;
    const station = createStationBase(def, ctx, {
        beacon: [-1.7, -1.5],
        beaconHeight: 2.4,
        panel: [1.7, 1.5],
        hitbox: { center: [0, 2.4, 0], size: [3.2, 4.8, 3.2] },
        haloRadius: 2.2,
        anchor: [0, 5.4, 0],
    });
    const b = station.body;

    // Tanque, tapa y tolva cónica
    b.add(part(g.cylinder, m.metal, [2.2, 1.8, 2.2], [0, 3.2, 0], { shadows }));
    b.add(part(g.cone, m.metal, [2.2, 0.95, 2.2], [0, 1.83, 0], { shadows }));
    b.add(part(g.cylinder, m.metalDark, [2.32, 0.1, 2.32], [0, 4.1, 0], { shadows: false }));
    b.add(part(g.cylinder, m.accent, [2.24, 0.14, 2.24], [0, 2.6, 0], { shadows: false }));

    // Patas
    for (const [lx, lz] of [[-1, -1], [1, -1], [-1, 1], [1, 1]]) {
        b.add(part(g.box, m.metalDark, [0.12, 4.1, 0.12], [lx * 0.95, 2.05, lz * 1.0], { shadows }));
    }

    // Motor-reductor y agitador superior (gira)
    b.add(part(g.box, m.metalDark, [0.6, 0.45, 0.6], [0, 4.38, 0], { shadows }));

    const rotor = new THREE.Group();
    rotor.position.set(0, 4.66, 0);
    rotor.add(part(g.roller, m.rubber, [0.95, 0.1, 0.95], [0, 0, 0], { shadows: false }));
    rotor.add(part(g.box, m.glow, [1.0, 0.04, 0.08], [0, 0.06, 0], { shadows: false }));
    b.add(rotor);

    station.animate = (t, dt, { speedFactor }) => {
        if (isActive(station.status)) {
            rotor.rotation.y += dt * 0.9 * speedFactor;
        }
    };

    return station;
}

/*
| 2. Procesamiento: horno túnel. La banda lo atraviesa; el cambio de
| producto crudo → horneado ocurre dentro.
*/
function buildOven(def, ctx) {
    const { geometries: g, materials: m, shadows } = ctx;
    const station = createStationBase(def, ctx, {
        beacon: [-2.9, -1.4],
        beaconHeight: 2.6,
        panel: [3.05, 1.35],
        hitbox: { center: [0, 1.5, 0], size: [5.8, 3, 2.6] },
        haloRadius: 3.4,
        anchor: [0, 3.9, 0],
    });
    const b = station.body;
    const L = 5.2;

    // Paredes, techo y franja de marca
    b.add(part(g.box, m.body, [L, 1.75, 0.16], [0, 1.22, 0.95], { shadows }));
    b.add(part(g.box, m.body, [L, 1.75, 0.16], [0, 1.22, -0.95], { shadows }));
    b.add(part(g.box, m.body, [L + 0.2, 0.26, 2.12], [0, 2.2, 0], { shadows }));
    b.add(part(g.box, m.accent, [L + 0.22, 0.1, 2.14], [0, 2.02, 0], { shadows: false }));

    // Marcos de boca del túnel
    for (const side of [-1, 1]) {
        b.add(part(g.box, m.metalDark, [0.12, 1.85, 2.14], [side * (L / 2 + 0.06), 1.2, 0], { shadows }));
    }

    // Ventana de calor (pulsa suavemente)
    const heatStrip = part(g.box, m.heat, [L - 1, 0.16, 0.02], [0, 1.55, 1.04], { shadows: false });
    b.add(heatStrip);

    // Chimeneas de extracción
    for (const sx of [-1.5, 1.5]) {
        b.add(part(g.cylinder, m.metal, [0.36, 1.1, 0.36], [sx, 2.88, -0.3], { shadows }));
        b.add(part(g.cylinder, m.metalDark, [0.5, 0.1, 0.5], [sx, 3.45, -0.3], { shadows: false }));
    }

    station.animate = (t) => {
        const on = isActive(station.status);
        m.heat.emissiveIntensity = on ? 1.0 + Math.sin(t * 1.3) * 0.25 : 0.15;
    };

    return station;
}

/*
| 3. Empaquetado: pórtico con prensa sincronizada al paso de productos.
| La prensa baja justo cuando el producto horneado se convierte en caja.
*/
function buildPacker(def, ctx) {
    const { geometries: g, materials: m, shadows } = ctx;
    const station = createStationBase(def, ctx, {
        beacon: [-1.6, -1.6],
        beaconHeight: 2.4,
        panel: [1.5, 1.45],
        hitbox: { center: [0, 1.5, -0.3], size: [3.2, 3, 3.4] },
        haloRadius: 2.3,
        anchor: [0, 3.6, 0],
    });
    const b = station.body;
    const beamY = 2.65;
    const headBottom = 1.46; // justo sobre la caja
    const lift = 0.75;

    // Pórtico
    for (const side of [-1, 1]) {
        b.add(part(g.box, m.metal, [0.16, beamY, 0.16], [0, beamY / 2, side * 0.92], { shadows }));
    }
    b.add(part(g.box, m.body, [0.42, 0.28, 2.1], [0, beamY, 0], { shadows }));
    b.add(part(g.box, m.accent, [0.44, 0.08, 2.12], [0, beamY + 0.12, 0], { shadows: false }));

    // Vástago (se estira) y cabezal
    const rod = part(g.cylinder, m.metal, [0.12, 1, 0.12], [0, 0, 0], { shadows: false });
    b.add(rod);
    const head = new THREE.Group();
    head.add(part(g.box, m.body, [0.82, 0.26, 0.8], [0, 0, 0], { shadows }));
    head.add(part(g.box, m.glow, [0.6, 0.03, 0.02], [0, 0, 0.41], { shadows: false }));
    b.add(head);

    // Desbobinadora de film a un costado (rollo gira)
    b.add(part(g.box, m.body, [1.5, 1.3, 0.8], [0, 0.65, -1.55], { shadows }));
    const filmRoll = part(g.roller, m.tape, [0.55, 1.1, 0.55], [0, 1.55, -1.55], { shadows: false });
    filmRoll.rotation.z = Math.PI / 2;
    b.add(filmRoll);

    let down = 0;

    station.animate = (t, dt, { flow, speedFactor }) => {
        if (isActive(station.status) && flow) {
            const phase = flow.phaseAt(def.x);
            const distance = Math.min(phase, 1 - phase);
            down = Math.max(0, 1 - distance / 0.2) ** 2;
            filmRoll.rotation.x += dt * 1.2 * speedFactor;
        } else {
            down += (0 - down) * Math.min(dt * 3, 1);
        }

        const headY = headBottom + 0.13 + lift * (1 - down);
        head.position.y = headY;

        const rodLength = beamY - headY;
        rod.scale.y = Math.max(rodLength, 0.01);
        rod.position.y = headY + rodLength / 2;
    };

    return station;
}

/*
| 4. Producto terminado: arco de escaneo, encintadora (donde los productos
| salen de la banda), tarima con cajas y brazo robótico.
*/
function buildPalletizer(def, ctx, { robot = true } = {}) {
    const { geometries: g, materials: m, shadows } = ctx;
    const station = createStationBase(def, ctx, {
        beacon: [0.2, -1.8],
        beaconHeight: 2.3,
        panel: [-0.4, 1.6],
        hitbox: { center: [2.2, 1.4, 0], size: [7, 2.8, 4] },
        haloRadius: 3.6,
        anchor: [1.6, 3.4, 0],
    });
    const b = station.body;

    // Arco de escaneo con barra de luz que sube y baja
    const archX = -1;
    for (const side of [-1, 1]) {
        b.add(part(g.box, m.metalDark, [0.14, 2.1, 0.14], [archX, 1.05, side * 0.85], { shadows }));
    }
    b.add(part(g.box, m.metalDark, [0.18, 0.14, 1.84], [archX, 2.1, 0], { shadows }));
    const scanBar = part(g.box, m.glow, [0.05, 0.035, 1.6], [archX, 1.5, 0], { shadows: false });
    b.add(scanBar);

    // Encintadora / etiquetadora al final de la banda (oculta la salida)
    const tx = 1.8;
    b.add(part(g.box, m.body, [1.6, 0.2, 1.8], [tx, 1.95, 0], { shadows }));
    for (const side of [-1, 1]) {
        b.add(part(g.box, m.body, [1.6, 1.25, 0.14], [tx, 1.3, side * 0.86], { shadows }));
    }
    b.add(part(g.box, m.accent, [1.62, 0.08, 1.82], [tx, 1.8, 0], { shadows: false }));

    // Tarima con producto terminado
    const palletX = 4.6;
    b.add(part(g.box, m.pallet, [1.5, 0.16, 1.3], [palletX, 0.08, 0], { shadows }));
    for (let layer = 0; layer < 3; layer++) {
        for (const dx of [-0.36, 0.36]) {
            for (const dz of [-0.32, 0.32]) {
                b.add(part(g.box, ctx.boxFaces ?? m.box, [0.66, 0.42, 0.58], [palletX + dx, 0.37 + layer * 0.43, dz], { shadows }));
            }
        }
    }

    // Brazo robótico (movimiento lento entre la encintadora y la tarima)
    let turret = null;
    let shoulder = null;

    if (robot) {
        const rx = 3.2;
        const rz = -1.5;
        b.add(part(g.cylinder, m.metalDark, [0.75, 0.3, 0.75], [rx, 0.15, rz], { shadows }));

        turret = new THREE.Group();
        turret.position.set(rx, 0.3, rz);
        turret.add(part(g.cylinder, m.accent, [0.5, 0.35, 0.5], [0, 0.17, 0], { shadows }));
        b.add(turret);

        shoulder = new THREE.Group();
        shoulder.position.set(0, 0.4, 0);
        turret.add(shoulder);

        shoulder.add(part(g.box, m.body, [0.2, 1.4, 0.2], [0, 0.7, 0], { shadows }));

        const elbow = new THREE.Group();
        elbow.position.set(0, 1.4, 0);
        elbow.rotation.z = -1.9;
        shoulder.add(elbow);

        elbow.add(part(g.box, m.body, [0.16, 1.2, 0.16], [0, 0.6, 0], { shadows }));
        elbow.add(part(g.box, m.metalDark, [0.3, 0.14, 0.3], [0, 1.25, 0], { shadows }));
    }

    station.animate = (t, dt, { speedFactor }) => {
        const on = isActive(station.status);

        if (on) {
            scanBar.position.y = 1.35 + Math.sin(t * 1.6) * 0.45;
        }

        if (turret && on) {
            const cycle = t * 0.35 * Math.max(speedFactor, 0.4);
            turret.rotation.y = -0.6 + Math.sin(cycle) * 0.75;
            shoulder.rotation.z = 0.45 + Math.sin(cycle * 2) * 0.12;
        }
    };

    return station;
}

const BUILDERS = {
    mixer: buildMixer,
    oven: buildOven,
    packer: buildPacker,
    palletizer: buildPalletizer,
};

export function createStations(defs, ctx, extra = {}) {
    return defs
        .filter((def) => BUILDERS[def.type])
        .map((def) => BUILDERS[def.type](def, ctx, extra));
}

/*
| Aplica un estado a la estación: color de torreta y halo.
*/
export function setStationStatus(station, status) {
    const normalized = normalizeStatus(status);
    const color = STATUS_COLORS[normalized];

    station.status = normalized;
    station.group.userData.status = normalized;
    station.beaconMaterial.color.set(color);
    station.beaconMaterial.emissive.set(color);
    station.haloMaterial.color.set(color);
}

/*
| Animación común: pulso de torreta según estado + respuesta a hover.
*/
export function animateStation(station, t, dt, ctx) {
    const blend = ctx.instant ? 1 : 1 - Math.exp(-dt * 8);
    station.hover += (station.hoverTarget - station.hover) * blend;

    const h = station.hover;
    const scale = 1 + h * 0.035;
    station.body.scale.set(scale, scale, scale);

    let pulse;
    switch (station.status) {
        case 'warning':
            pulse = 1.2 + Math.max(Math.sin(t * 5), 0) * 1.4;
            break;
        case 'stopped':
            pulse = 0.6 + (Math.sin(t * 2.2) > 0 ? 1.4 : 0);
            break;
        case 'maintenance':
            pulse = 1.5;
            break;
        default:
            pulse = 1.1 + Math.sin(t * 1.4) * 0.35;
    }

    if (ctx.instant) pulse = 1.5;

    station.beaconMaterial.emissiveIntensity = pulse + h * 1.5;
    station.haloMaterial.opacity = ctx.haloOpacity + h * 0.32;

    station.animate?.(t, dt, ctx);
}
