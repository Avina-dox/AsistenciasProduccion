import * as THREE from 'three';
import { STATIONS, WORKER_STATUS_COLORS } from './config.js';

/*
|--------------------------------------------------------------------------
| Trabajadores de la planta
|--------------------------------------------------------------------------
| Cada trabajador es un THREE.Group (worker.root) con:
|   root.userData = { id, station, status, animation }
|
| Visual: un maniquí procedural con el uniforme sanitario de la planta
| (cofia blanca, cubrebocas azul claro, orejeras azules, camiseta blanca
| de manga corta, mandil morado oscuro, pantalón blanco y calzado oscuro).
| Si existe un GLB en resources/models/workers/ con el nombre de
| `def.model`, se carga con GLTFLoader y reemplaza al maniquí; el resto
| (posición, estados, walkTo, hover) no cambia.
|
| API:
|   createWorker(def, kit)            → trabajador con maniquí procedural
|   loadWorkerModel(url)              → { scene, animations } (cacheado y clonado)
|   placeWorker(worker, [x, z], yaw)
|   setWorkerState(worker, state)     → working | idle | walking | break | offline
|   worker.walkTo([x, z], { then, facing })
|   animateWorker(worker, t, dt, ctx)
*/

export const WORKER_STATES = Object.keys(WORKER_STATUS_COLORS);

// Altura del maniquí a escala 1 (m). Cada trabajador se escala a def.height.
const BASE_HEIGHT = 1.78; // del piso a la diadema de las orejeras
const WALK_SPEED = 1.0; // m/s
const STRIDE = 5.2; // radianes de ciclo de paso por metro

/*
| GLB disponibles en resources/models/workers (resueltos por Vite en el build).
| Si la carpeta está vacía el objeto queda vacío y se usan maniquíes.
*/
const MODEL_FILES = import.meta.glob('../../models/workers/*.{glb,gltf}', {
    eager: true,
    query: '?url',
    import: 'default',
});

const MODEL_URLS = Object.fromEntries(
    Object.entries(MODEL_FILES).map(([path, url]) => [path.split('/').pop(), url]),
);

/*
| URL del modelo de un trabajador: su archivo, o cualquier otro disponible
| (los GLB se reutilizan entre trabajadores). null = maniquí procedural.
*/
export function resolveWorkerModelUrl(def) {
    if (!def.model) return null;
    if (/^(https?:)?\//.test(def.model)) return def.model;

    return MODEL_URLS[def.model] ?? Object.values(MODEL_URLS)[0] ?? null;
}

/*
|--------------------------------------------------------------------------
| Carga de GLB (GLTFLoader se descarga sólo si hay modelos)
|--------------------------------------------------------------------------
*/
const modelCache = new Map();

export async function loadWorkerModel(url) {
    if (!modelCache.has(url)) {
        modelCache.set(url, (async () => {
            const [{ GLTFLoader }] = await Promise.all([import('three/addons/loaders/GLTFLoader.js')]);

            return new GLTFLoader().loadAsync(url);
        })());
    }

    const gltf = await modelCache.get(url);
    const { clone } = await import('three/addons/utils/SkeletonUtils.js');

    // SkeletonUtils.clone respeta esqueletos: un mismo GLB sirve a varios trabajadores.
    return { scene: clone(gltf.scene), animations: gltf.animations };
}

/*
|--------------------------------------------------------------------------
| Kit compartido: geometrías y materiales del uniforme
|--------------------------------------------------------------------------
*/
function createKit({ shadows, isMobile }) {
    const seg = isMobile ? 10 : 16;

    const geometries = {
        box: new THREE.BoxGeometry(1, 1, 1),
        cylinder: new THREE.CylinderGeometry(0.5, 0.5, 1, seg),
        limb: new THREE.CylinderGeometry(0.5, 0.44, 1, seg), // se adelgaza hacia abajo
        sphere: new THREE.SphereGeometry(0.5, seg, Math.round(seg * 0.75)),
        capsule: new THREE.CapsuleGeometry(0.5, 1, 4, seg),
        // Cofia: domo algo mayor que media esfera (cubre la nuca al inclinarse).
        cap: new THREE.SphereGeometry(0.5, seg + 2, 10, 0, Math.PI * 2, 0, Math.PI * 0.62),
        // Cubrebocas: media "taza" abierta al frente de la cara.
        mask: new THREE.CylinderGeometry(0.5, 0.46, 1, seg, 1, true, -Math.PI / 2, Math.PI),
        // Diadema de las orejeras: medio toro sobre la cabeza.
        band: new THREE.TorusGeometry(0.5, 0.06, 6, 20, Math.PI),
    };

    const std = (color, extra = {}) => new THREE.MeshStandardMaterial({ color, roughness: 0.8, ...extra });

    const materials = {
        shirt: std('#F3F4F7', { roughness: 0.85 }),
        pants: std('#E9ECF1', { roughness: 0.85 }),
        cap: std('#FBFBFC', { roughness: 0.95 }),
        apron: std('#4A1F5C', { roughness: 0.6 }),
        apronTrim: std('#3A1749', { roughness: 0.6 }),
        mask: std('#A9D8F2', { roughness: 0.9, side: THREE.DoubleSide }),
        earmuff: std('#2F6FD6', { roughness: 0.45 }),
        earmuffBand: std('#244F9E', { roughness: 0.5 }),
        shoes: std('#23262C', { roughness: 0.55 }),
        eyes: std('#1D1D22', { roughness: 0.4 }),
        skin: [std('#E2B793'), std('#C48F68'), std('#9C6B4B')],
        hitbox: new THREE.MeshBasicMaterial(),
    };

    return { geometries, materials, shadows };
}

/*
|--------------------------------------------------------------------------
| Maniquí procedural
|--------------------------------------------------------------------------
| Jerarquía de articulaciones (rotaciones en radianes, mirando a +Z):
|   hips → torso → head
|              → shoulderA/B → elbowA/B   (A = lado +X)
|        → legA/B → kneeA/B
*/
function buildMannequin(kit, def) {
    const { geometries: g, materials: m, shadows } = kit;
    const skin = m.skin[def.skin % m.skin.length];

    const mesh = (geometry, material, scale, position, rotation) => {
        const obj = new THREE.Mesh(geometry, material);
        obj.scale.set(...scale);
        obj.position.set(...position);
        if (rotation) obj.rotation.set(...rotation);
        obj.castShadow = shadows;
        obj.receiveShadow = false;

        return obj;
    };

    const joint = (parent, position) => {
        const j = new THREE.Group();
        j.position.set(...position);
        parent.add(j);

        return j;
    };

    const figure = new THREE.Group();

    // Cadera y pelvis
    const hips = joint(figure, [0, 0.95, 0]);
    hips.add(mesh(g.cylinder, m.pants, [0.32, 0.18, 0.21], [0, 0.01, 0])); // pelvis redondeada

    // Piernas (pantalón blanco + calzado de seguridad)
    const legs = {};
    for (const [key, side] of [['A', 1], ['B', -1]]) {
        const leg = joint(hips, [side * 0.09, -0.04, 0]);
        leg.add(mesh(g.limb, m.pants, [0.14, 0.44, 0.14], [0, -0.22, 0]));

        const knee = joint(leg, [0, -0.44, 0]);
        knee.add(mesh(g.limb, m.pants, [0.115, 0.4, 0.115], [0, -0.2, 0]));
        knee.add(mesh(g.box, m.shoes, [0.11, 0.08, 0.27], [0, -0.43, 0.04]));
        knee.add(mesh(g.box, m.shoes, [0.112, 0.025, 0.28], [0, -0.465, 0.04])); // suela

        legs[key] = { leg, knee };
    }

    // Torso: camiseta blanca
    const torso = joint(hips, [0, 0.05, 0]);
    const chest = mesh(g.capsule, m.shirt, [0.36, 0.17, 0.23], [0, 0.21, 0]);
    chest.scale.set(0.36, 0.24, 0.23); // CapsuleGeometry(0.5, 1) mide 2 de alto → 0.48 m
    torso.add(chest);

    // Mandil morado: peto, faldón (articulado para que no lo atraviesen las rodillas),
    // tirantes, cinturón y bolsa.
    torso.add(mesh(g.box, m.apron, [0.3, 0.4, 0.025], [0, 0.22, 0.118]));
    torso.add(mesh(g.box, m.apronTrim, [0.13, 0.08, 0.01], [0, 0.26, 0.134])); // bolsa
    torso.add(mesh(g.cylinder, m.apronTrim, [0.37, 0.045, 0.24], [0, 0.1, 0])); // cinturón (sigue el contorno)
    for (const side of [-1, 1]) {
        torso.add(mesh(g.box, m.apronTrim, [0.035, 0.12, 0.02], [side * 0.11, 0.46, 0.09], [-0.35, 0, side * 0.25]));
    }

    const skirt = joint(torso, [0, 0.02, 0.125]);
    skirt.add(mesh(g.box, m.apron, [0.42, 0.44, 0.022], [0, -0.22, 0]));

    // Cuello y cabeza
    torso.add(mesh(g.cylinder, skin, [0.1, 0.08, 0.1], [0, 0.5, 0]));
    const head = joint(torso, [0, 0.52, 0]);
    head.add(mesh(g.sphere, skin, [0.19, 0.23, 0.2], [0, 0.11, 0]));

    // Ojos (lo único visible del rostro entre cofia y cubrebocas)
    for (const side of [-1, 1]) {
        head.add(mesh(g.sphere, m.eyes, [0.022, 0.016, 0.012], [side * 0.04, 0.128, 0.097]));
    }

    // Cofia blanca: cubre todo el cabello, inclinada hacia la nuca
    head.add(mesh(g.cap, m.cap, [0.22, 0.25, 0.235], [0, 0.125, -0.012], [-0.48, 0, 0]));

    // Cubrebocas azul claro
    head.add(mesh(g.mask, m.mask, [0.205, 0.085, 0.215], [0, 0.065, 0.004]));

    // Protección auditiva: copas + diadema
    for (const side of [-1, 1]) {
        head.add(mesh(g.cylinder, m.earmuff, [0.085, 0.045, 0.085], [side * 0.105, 0.11, -0.005], [0, 0, Math.PI / 2]));
    }
    head.add(mesh(g.band, m.earmuffBand, [0.27, 0.3, 0.27], [0, 0.11, -0.005]));

    // Brazos: manga corta blanca, antebrazo y mano
    const arms = {};
    for (const [key, side] of [['A', 1], ['B', -1]]) {
        const shoulder = joint(torso, [side * 0.205, 0.42, 0]);
        shoulder.add(mesh(g.sphere, m.shirt, [0.11, 0.11, 0.11], [0, -0.01, 0]));
        shoulder.add(mesh(g.limb, m.shirt, [0.118, 0.15, 0.118], [0, -0.065, 0]));
        shoulder.add(mesh(g.limb, skin, [0.085, 0.16, 0.085], [0, -0.2, 0]));

        const elbow = joint(shoulder, [0, -0.28, 0]);
        elbow.add(mesh(g.limb, skin, [0.075, 0.24, 0.075], [0, -0.12, 0]));
        elbow.add(mesh(g.sphere, skin, [0.07, 0.1, 0.045], [0, -0.27, 0]));

        arms[key] = { shoulder, elbow, side };
    }

    return {
        figure,
        joints: { hips, torso, head, skirt, chest, arms, legs },
    };
}

/*
|--------------------------------------------------------------------------
| Poses
|--------------------------------------------------------------------------
| Cada función escribe ángulos objetivo en `p` (objeto reutilizado: no se
| crean objetos por frame). El animador interpola hacia ellos, así los
| cambios de estado se mezclan solos.
*/
const POSE_KEYS = [
    'lean', 'twist', 'headX', 'headY',
    'armAF', 'armAZ', 'elbowA', 'armBF', 'armBZ', 'elbowB',
    'legAF', 'kneeA', 'legBF', 'kneeB', 'bob', 'breath',
];

function emptyPose() {
    return Object.fromEntries(POSE_KEYS.map((k) => [k, 0]));
}

function poseIdle(p, t, w) {
    const ph = w.phase;
    p.lean = 0.02 + w.posture;
    p.twist = 0;
    p.headX = 0.05 + Math.sin(t * 0.4 + ph) * 0.03;
    p.headY = Math.sin(t * 0.23 + ph) * 0.25;
    p.armAF = 0.06 + Math.sin(t * 0.8 + ph) * 0.02;
    p.armBF = 0.04 + Math.sin(t * 0.8 + ph + 1) * 0.02;
    p.armAZ = 0.07;
    p.armBZ = 0.07;
    p.elbowA = 0.18;
    p.elbowB = 0.2;
    p.legAF = 0;
    p.legBF = 0;
    p.kneeA = 0.03;
    p.kneeB = 0.03;
    p.bob = 0;
    p.breath = Math.sin(t * 1.4 + ph) * 0.012;
}

const WORK_POSES = {
    // Amasa / porciona sobre la mesa de preparación.
    preparation(p, t, w) {
        const c = t * 1.5 + w.phase;
        p.lean = 0.22 + w.posture;
        p.twist = Math.sin(c * 0.5) * 0.04;
        p.headX = 0.45;
        p.headY = Math.sin(t * 0.3) * 0.08;
        p.armAF = 0.55 + Math.sin(c) * 0.12;
        p.armBF = 0.55 + Math.sin(c + Math.PI) * 0.12;
        p.armAZ = -0.06;
        p.armBZ = -0.06;
        p.elbowA = 0.75 + Math.cos(c) * 0.15;
        p.elbowB = 0.75 + Math.cos(c + Math.PI) * 0.15;
        p.kneeA = p.kneeB = 0.06;
    },

    // Opera el panel del horno y vigila la máquina.
    processing(p, t, w) {
        const tap = Math.sin(t * 2.1 + w.phase);
        p.lean = 0.06 + w.posture;
        p.twist = 0;
        p.headX = 0.12;
        // Alterna la mirada entre el panel (al frente) y el horno (a su +X).
        p.headY = Math.sin(t * 0.2 + w.phase) > 0.55 ? 0.7 : 0.05;
        p.armAF = 1.15 + tap * 0.05;
        p.armAZ = -0.15;
        p.elbowA = 0.55 + Math.max(0, tap) * 0.1;
        p.armBF = 0.08;
        p.armBZ = 0.07;
        p.elbowB = 0.2;
        p.kneeA = p.kneeB = 0.03;
    },

    // Acomoda producto en la banda antes de empacar.
    packaging(p, t, w) {
        const c = t * 1.2 + w.phase;
        const s = 0.5 + 0.5 * Math.sin(c);
        p.lean = 0.28 + w.posture;
        p.twist = Math.sin(c * 0.5) * 0.12;
        p.headX = 0.4;
        p.headY = p.twist * 0.6;
        p.armAF = 0.6 + s * 0.45;
        p.armBF = 0.6 + s * 0.45;
        p.armAZ = -0.08;
        p.armBZ = -0.08;
        p.elbowA = 1.0 - s * 0.45;
        p.elbowB = 1.0 - s * 0.45;
        p.kneeA = p.kneeB = 0.08;
    },

    // Revisa cajas terminadas (manos al frente, mirada recorriendo la banda).
    inspection(p, t, w) {
        p.lean = 0.15 + w.posture;
        p.twist = 0;
        p.headX = 0.35;
        p.headY = Math.sin(t * 0.5 + w.phase) * 0.3;
        p.armAF = 0.35;
        p.armBF = 0.3;
        p.armAZ = -0.05;
        p.armBZ = -0.05;
        p.elbowA = 1.1;
        p.elbowB = 1.2;
        p.kneeA = p.kneeB = 0.04;
    },
};

function poseWalking(p, t, w) {
    const s = w.walkPhase;
    p.lean = 0.06;
    p.twist = Math.sin(s) * 0.05;
    p.headX = 0.05;
    p.headY = 0;
    p.legAF = Math.sin(s) * 0.42;
    p.legBF = -Math.sin(s) * 0.42;
    p.kneeA = 0.1 + Math.max(0, -Math.sin(s - 0.6)) * 0.55;
    p.kneeB = 0.1 + Math.max(0, Math.sin(s - 0.6)) * 0.55;
    p.armAF = -Math.sin(s) * 0.32;
    p.armBF = Math.sin(s) * 0.32;
    p.armAZ = 0.06;
    p.armBZ = 0.06;
    p.elbowA = 0.3;
    p.elbowB = 0.3;
    p.bob = (Math.cos(2 * s) * 0.5 + 0.5) * 0.025;
    p.breath = 0;
}

function poseBreak(p, t, w) {
    poseIdle(p, t, w);
    // Manos atrás, postura relajada.
    p.lean = -0.03;
    p.armAF = -0.18;
    p.armBF = -0.18;
    p.armAZ = 0.04;
    p.armBZ = 0.04;
    p.elbowA = 0.35;
    p.elbowB = 0.35;
    p.headY = Math.sin(t * 0.15 + w.phase) * 0.4;
}

function computePose(p, worker, t) {
    switch (worker.animation) {
        case 'walking':
            poseWalking(p, t, worker);
            break;
        case 'working':
            poseIdle(p, t, worker); // base (respiración, piernas)
            (WORK_POSES[worker.task] ?? WORK_POSES.inspection)(p, t, worker);
            break;
        case 'break':
            poseBreak(p, t, worker);
            break;
        default:
            poseIdle(p, t, worker);
    }
}

function applyPose(rig, c) {
    const { hips, torso, head, skirt, chest, arms, legs } = rig;

    hips.position.y = 0.95 + c.bob;
    torso.rotation.set(c.lean, c.twist, 0);
    head.rotation.set(c.headX, c.headY, 0);
    chest.scale.y = 0.24 * (1 + c.breath);

    // Forward = -rotation.x (el personaje mira a +Z); abducción hacia afuera por lado.
    arms.A.shoulder.rotation.set(-c.armAF, 0, c.armAZ);
    arms.B.shoulder.rotation.set(-c.armBF, 0, -c.armBZ);
    arms.A.elbow.rotation.x = -c.elbowA;
    arms.B.elbow.rotation.x = -c.elbowB;

    legs.A.leg.rotation.x = -c.legAF;
    legs.B.leg.rotation.x = -c.legBF;
    legs.A.knee.rotation.x = c.kneeA;
    legs.B.knee.rotation.x = c.kneeB;

    // El faldón cuelga vertical y lo empuja la pierna que va adelante.
    skirt.rotation.x = -c.lean - Math.max(c.legAF, c.legBF, 0) * 0.75;
}

/*
|--------------------------------------------------------------------------
| Trabajador
|--------------------------------------------------------------------------
*/
function angleDelta(from, to) {
    return Math.atan2(Math.sin(to - from), Math.cos(to - from));
}

export function createWorker(def, kit) {
    const root = new THREE.Group();
    root.name = `worker:${def.id}`;

    const scale = (def.height ?? BASE_HEIGHT) / BASE_HEIGHT;
    const body = new THREE.Group(); // contenedor visual (maniquí o GLB); escala de altura + hover
    body.scale.setScalar(scale);
    root.add(body);

    const mannequin = buildMannequin(kit, def);
    body.add(mannequin.figure);

    // Volumen invisible para hover
    const hitbox = new THREE.Mesh(kit.geometries.box, kit.materials.hitbox);
    hitbox.visible = false;
    hitbox.scale.set(0.7, 1.85, 0.6);
    hitbox.position.y = 0.92;
    hitbox.userData.targetId = def.id;
    body.add(hitbox);

    // Variación determinista por id (fase, postura)
    const seed = [...def.id].reduce((acc, ch) => acc + ch.charCodeAt(0), 0);

    const worker = {
        kind: 'worker',
        id: def.id,
        role: def.role ?? 'Operador',
        stationId: def.station,
        task: def.task,
        def,
        root,
        body,
        scale,
        rig: mannequin.joints,
        model: null,
        mixer: null,
        actions: {},
        currentAction: null,
        hitbox,
        anchor: new THREE.Vector3(),
        height: def.height ?? BASE_HEIGHT,
        status: 'working',
        animation: 'working',
        facing: def.facing ?? 0,
        phase: (seed % 628) / 100,
        posture: ((seed % 7) - 3) * 0.012,
        walkPhase: 0,
        walk: null,
        patrolIndex: 0,
        dwell: 0,
        hover: 0,
        hoverTarget: 0,
        interactive: true,
        pose: emptyPose(),
        current: emptyPose(),

        walkTo(target, options = {}) {
            const [x, z] = Array.isArray(target) ? target : [target.x, target.z];
            worker.walk = {
                x,
                z,
                then: options.then ?? 'idle',
                facing: options.facing ?? null,
            };
            setWorkerState(worker, 'walking');
        },
    };

    placeWorker(worker, def.position, worker.facing);
    syncUserData(worker);

    return worker;
}

export function placeWorker(worker, [x, z], facing = worker.facing) {
    worker.root.position.set(x, 0, z);
    worker.root.rotation.y = facing;
    worker.facing = facing;
}

function syncUserData(worker) {
    worker.root.userData = {
        id: worker.id,
        station: worker.stationId,
        status: worker.status,
        animation: worker.animation,
    };
}

export function setWorkerState(worker, state) {
    const next = WORKER_STATES.includes(state) ? state : 'working';
    worker.status = next;

    if (next !== 'walking') worker.walk = null;

    const offline = next === 'offline';
    worker.root.visible = !offline;
    worker.interactive = !offline;

    syncUserData(worker);
}

/*
| Reemplaza el maniquí por un GLB ya cargado. El modelo se normaliza a la
| altura del trabajador, con los pies en y = 0 y mirando a +Z.
*/
export function applyWorkerModel(worker, { scene, animations }, kit) {
    const box = new THREE.Box3().setFromObject(scene);
    const size = box.getSize(new THREE.Vector3());

    if (size.y <= 0) return;

    const s = BASE_HEIGHT / size.y; // el contenedor `body` ya aplica la altura del trabajador
    scene.scale.multiplyScalar(s);
    const center = box.getCenter(new THREE.Vector3()).multiplyScalar(s);
    scene.position.set(-center.x, -box.min.y * s, -center.z);

    scene.traverse((obj) => {
        if (obj.isMesh) {
            obj.castShadow = kit.shadows;
            obj.receiveShadow = false;
        }
    });

    worker.body.remove(worker.rig.hips.parent);
    worker.body.add(scene);
    worker.model = scene;
    worker.rig = null;

    if (animations?.length) {
        worker.mixer = new THREE.AnimationMixer(scene);
        const find = (...names) => animations.find((clip) => names.some((n) => clip.name.toLowerCase().includes(n)));

        for (const [state, clip] of [
            ['idle', find('idle')],
            ['working', find('work', 'action', 'operate')],
            ['walking', find('walk')],
            ['break', find('break', 'rest', 'idle')],
        ]) {
            if (clip) worker.actions[state] = worker.mixer.clipAction(clip);
        }
    }
}

function playModelAction(worker) {
    const action = worker.actions[worker.animation] ?? worker.actions.idle;
    if (!action || action === worker.currentAction) return;

    action.reset().fadeIn(0.4).play();
    worker.currentAction?.fadeOut(0.4);
    worker.currentAction = action;
}

/*
| Un frame del trabajador: patrulla, desplazamiento, giro, pose y hover.
| ctx = { instant, activity, stationActive(id) }
*/
export function animateWorker(worker, t, dt, ctx) {
    if (!worker.root.visible) return;

    const time = t * ctx.activity;
    const step = dt * ctx.activity;

    // Patrulla: trabaja un rato en cada punto y camina al siguiente.
    const patrol = worker.def.patrol;
    if (patrol?.length && worker.status === 'working' && !worker.walk && step > 0) {
        worker.dwell += step;

        if (worker.dwell >= patrol[worker.patrolIndex].dwell) {
            worker.dwell = 0;
            worker.patrolIndex = (worker.patrolIndex + 1) % patrol.length;
            const stop = patrol[worker.patrolIndex];
            worker.walkTo(stop.position, { then: 'working', facing: stop.facing });
        }
    }

    // Caminar: girar hacia el destino, avanzar, llegar y cambiar de estado.
    if (worker.walk && step > 0) {
        const { root } = worker;
        const dx = worker.walk.x - root.position.x;
        const dz = worker.walk.z - root.position.z;
        const distance = Math.hypot(dx, dz);
        const heading = Math.atan2(dx, dz);
        const turn = angleDelta(root.rotation.y, heading);

        root.rotation.y += turn * Math.min(step * 4, 1);
        worker.walkPhase += step * 2; // pasos cortos al girar

        if (Math.abs(turn) < 0.35) {
            const move = Math.min(WALK_SPEED * step, distance);
            root.position.x += (dx / (distance || 1)) * move;
            root.position.z += (dz / (distance || 1)) * move;
            worker.walkPhase += move * STRIDE;
        }

        if (distance < 0.04) {
            const { then, facing } = worker.walk;
            worker.walk = null;
            if (facing !== null) worker.facing = facing;
            setWorkerState(worker, then);
        }
    } else if (!worker.walk) {
        // Orientarse hacia su puesto.
        const turn = angleDelta(worker.root.rotation.y, worker.facing);
        worker.root.rotation.y += ctx.instant ? turn : turn * Math.min(step * 3, 1);
    }

    // Animación efectiva: si su estación está detenida, el operador espera.
    let animation = worker.status === 'offline' ? 'idle' : worker.status;
    if (animation === 'walking' && !worker.walk) animation = 'idle';
    if (animation === 'working' && !ctx.stationActive(worker.stationId)) animation = 'idle';

    if (animation !== worker.animation) {
        worker.animation = animation;
        syncUserData(worker);
    }

    // Hover: escala 1.0 → 1.03, sin brillo.
    const blend = ctx.instant ? 1 : 1 - Math.exp(-dt * 8);
    worker.hover += (worker.hoverTarget - worker.hover) * blend;
    worker.body.scale.setScalar(worker.scale * (1 + worker.hover * 0.03));

    if (worker.rig) {
        computePose(worker.pose, worker, time);

        const k = ctx.instant ? 1 : 1 - Math.exp(-step * 6);
        for (const key of POSE_KEYS) {
            worker.current[key] += (worker.pose[key] - worker.current[key]) * k;
        }
        applyPose(worker.rig, worker.current);
    } else if (worker.mixer) {
        playModelAction(worker);
        worker.mixer.update(step);
    }

    worker.anchor.set(worker.root.position.x, worker.height * 1.03 + 0.3, worker.root.position.z);
}

/*
|--------------------------------------------------------------------------
| Sistema: crea los trabajadores del modo, carga GLB y aplica datos
|--------------------------------------------------------------------------
*/
export function createWorkerSystem({ defs, shadows, isMobile, activity = 1, onModelLoaded }) {
    const kit = createKit({ shadows, isMobile });
    const group = new THREE.Group();
    group.name = 'workers';

    const workers = defs.map((def) => {
        const worker = createWorker(def, kit);
        group.add(worker.root);

        const url = resolveWorkerModelUrl(def);
        if (url) {
            loadWorkerModel(url)
                .then((loaded) => {
                    applyWorkerModel(worker, loaded, kit);
                    onModelLoaded?.();
                })
                .catch((error) => console.warn(`[workers] No se pudo cargar ${url}; se usa el maniquí.`, error));
        }

        return worker;
    });

    const byId = new Map(workers.map((w) => [w.id, w]));

    function update(t, dt, { instant, stationActive }) {
        const ctx = { instant, activity, stationActive };
        workers.forEach((w) => animateWorker(w, t, dt, ctx));
    }

    function setData(list = []) {
        for (const item of list) {
            const worker = byId.get(item.id);
            if (!worker) continue;
            if (item.station) worker.stationId = item.station;
            if (item.status) setWorkerState(worker, item.status);
        }
    }

    function dispose() {
        workers.forEach((w) => w.mixer?.stopAllAction());
        Object.values(kit.geometries).forEach((geo) => geo.dispose());
        Object.values(kit.materials).flat().forEach((mat) => mat.dispose());
    }

    return { group, workers, byId, update, setData, dispose };
}

export function stationLabel(id) {
    return STATIONS.find((s) => s.id === id)?.label ?? id;
}
