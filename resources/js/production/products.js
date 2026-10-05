import * as THREE from 'three';

/*
|--------------------------------------------------------------------------
| Flujo de productos
|--------------------------------------------------------------------------
| Los productos avanzan sobre una banda con separación constante. Cada
| etapa (crudo → horneado → empacado) es un InstancedMesh con una instancia
| por producto: el producto "cambia" de etapa ocultando su instancia en una
| y mostrándola en la siguiente. Nada se crea dentro del render loop.
|
| `stages`: [{ at, layers: [{ geometry, material, scale, offset? }] }]
|   at     → coordenada X del mundo donde empieza la etapa
|   layers → piezas que componen el producto (p. ej. caja + cinta)
*/

const HIDDEN = new THREE.Matrix4().makeScale(0, 0, 0);

function smoothstep(edge0, edge1, x) {
    const t = Math.min(Math.max((x - edge0) / (edge1 - edge0), 0), 1);

    return t * t * (3 - 2 * t);
}

export function createProductFlow({
    from,
    to,
    z = 0,
    y = 0.98,
    spacing = 1.6,
    stages,
    shadows = true,
    fade = 0.6,
    seed = 7,
}) {
    const group = new THREE.Group();
    const direction = Math.sign(to - from) || 1;
    const pathLength = Math.abs(to - from);
    const count = Math.ceil(pathLength / spacing) + 1;
    const cycle = count * spacing;

    // Variación sutil de orientación por producto (determinista)
    let s = seed;
    const yaw = Array.from({ length: count }, () => {
        s = (s * 16807) % 2147483647;

        return ((s / 2147483647) - 0.5) * 0.12;
    });

    const builtStages = stages.map((stage) => ({
        start: (stage.at - from) * direction,
        layers: stage.layers.map((layer) => {
            const mesh = new THREE.InstancedMesh(layer.geometry, layer.material, count);

            mesh.castShadow = shadows;
            mesh.receiveShadow = shadows;
            mesh.instanceMatrix.setUsage(THREE.DynamicDrawUsage);
            // Las instancias recorren toda la línea: evitamos el culling por bounding sphere.
            mesh.frustumCulled = false;
            group.add(mesh);

            return {
                mesh,
                scale: layer.scale,
                offset: layer.offset ?? [0, 0, 0],
            };
        }),
    }));

    const dummy = new THREE.Object3D();
    let travelled = 0;

    function stageIndexAt(distance) {
        let index = 0;

        for (let i = 0; i < builtStages.length; i++) {
            if (distance >= builtStages[i].start) index = i;
        }

        return index;
    }

    function update(dt, speed) {
        travelled = (travelled + speed * dt) % cycle;

        for (let i = 0; i < count; i++) {
            const distance = (i * spacing + travelled) % cycle;
            const visible = distance <= pathLength;
            const active = visible ? stageIndexAt(distance) : -1;

            const grow = smoothstep(0, fade, distance) * (1 - smoothstep(pathLength - fade, pathLength, distance));
            const x = from + distance * direction;

            builtStages.forEach((stage, stageIndex) => {
                for (const layer of stage.layers) {
                    if (stageIndex !== active || grow <= 0) {
                        layer.mesh.setMatrixAt(i, HIDDEN);
                        continue;
                    }

                    dummy.position.set(x + layer.offset[0], y + layer.offset[1] + (layer.scale[1] * grow) / 2, z + layer.offset[2]);
                    dummy.rotation.set(0, yaw[i], 0);
                    dummy.scale.set(layer.scale[0] * grow, layer.scale[1] * grow, layer.scale[2] * grow);
                    dummy.updateMatrix();
                    layer.mesh.setMatrixAt(i, dummy.matrix);
                }
            });
        }

        for (const stage of builtStages) {
            for (const layer of stage.layers) {
                layer.mesh.instanceMatrix.needsUpdate = true;
            }
        }
    }

    /*
    | Fase (0..1) del producto más cercano respecto a una X del mundo:
    | 0 = hay un producto justo ahí. Sirve para sincronizar máquinas
    | (p. ej. la prensa de empaquetado baja cuando pasa un producto).
    */
    function phaseAt(worldX) {
        const target = (worldX - from) * direction;
        const raw = (travelled - target) % spacing;

        return (raw < 0 ? raw + spacing : raw) / spacing;
    }

    return { group, update, phaseAt, count };
}
