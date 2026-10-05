import * as THREE from 'three';
import { STATUS_COLORS, STATUS_LABELS } from './config.js';

/*
|--------------------------------------------------------------------------
| Interacción y etiquetas
|--------------------------------------------------------------------------
| El canvas SIEMPRE tiene pointer-events: none, así que nunca bloquea
| botones, inputs ni tarjetas. Para el hover escuchamos el puntero en
| window y sólo hacemos raycast cuando lo que está bajo el cursor es
| "fondo": body, html o un elemento marcado con
| [data-production-passthrough] (contenedores de layout, no tarjetas).
*/

function isPassthrough(target) {
    if (!(target instanceof Element)) return false;
    if (target === document.body || target === document.documentElement) return true;

    return target.hasAttribute('data-production-passthrough');
}

export function createInteractions({ camera, stations, onChange }) {
    const raycaster = new THREE.Raycaster();
    const pointer = new THREE.Vector2();
    const hitboxes = stations.map((s) => s.hitbox);
    const byId = new Map(stations.map((s) => [s.id, s]));

    let active = false;
    let dirty = false;
    let hovered = null;

    const onPointerMove = (event) => {
        if (event.pointerType === 'touch') return;

        active = isPassthrough(event.target);
        pointer.set((event.clientX / window.innerWidth) * 2 - 1, -(event.clientY / window.innerHeight) * 2 + 1);
        dirty = true;
        onChange?.();
    };

    const onPointerLeave = () => {
        active = false;
        dirty = true;
        onChange?.();
    };

    window.addEventListener('pointermove', onPointerMove, { passive: true });
    document.documentElement.addEventListener('pointerleave', onPointerLeave);
    window.addEventListener('blur', onPointerLeave);

    function setHovered(station) {
        if (hovered === station) return;

        if (hovered) hovered.hoverTarget = 0;
        hovered = station;
        if (hovered) hovered.hoverTarget = 1;
    }

    /*
    | Se llama desde el loop. Sólo hace raycast si el puntero se movió.
    */
    function update() {
        if (!dirty) return hovered;
        dirty = false;

        if (!active) {
            setHovered(null);

            return hovered;
        }

        raycaster.setFromCamera(pointer, camera);
        const hit = raycaster.intersectObjects(hitboxes, false)[0];
        setHovered(hit ? byId.get(hit.object.userData.stationId) : null);

        return hovered;
    }

    function dispose() {
        window.removeEventListener('pointermove', onPointerMove);
        document.documentElement.removeEventListener('pointerleave', onPointerLeave);
        window.removeEventListener('blur', onPointerLeave);
        setHovered(null);
    }

    return {
        update,
        dispose,
        get hovered() {
            return hovered;
        },
    };
}

/*
| Etiquetas HTML ancladas a cada estación (texto = HTML, no Three.js).
| Viven en una capa bajo el contenido: las tarjetas siempre quedan encima.
*/
export function createLabels(container, stations) {
    if (!container) return null;

    const projected = new THREE.Vector3();
    const items = stations.map((station) => {
        const el = document.createElement('div');
        el.className = 'production-label';
        el.innerHTML = `
            <span class="production-label__dot"></span>
            <span class="production-label__name"></span>
            <span class="production-label__status"></span>
        `;
        el.querySelector('.production-label__name').textContent = station.label;
        container.appendChild(el);

        return { station, el, status: null };
    });

    function update(camera, width, height) {
        for (const item of items) {
            const { station, el } = item;

            if (item.status !== station.status) {
                item.status = station.status;
                el.style.setProperty('--status-color', STATUS_COLORS[station.status]);
                el.querySelector('.production-label__status').textContent = STATUS_LABELS[station.status];
                el.dataset.status = station.status;
            }

            projected.copy(station.anchor).project(camera);

            const behind = projected.z > 1;
            const x = (projected.x * 0.5 + 0.5) * width;
            const y = (-projected.y * 0.5 + 0.5) * height;

            el.style.transform = `translate3d(${x.toFixed(1)}px, ${y.toFixed(1)}px, 0) translate(-50%, -100%)`;
            el.classList.toggle('is-hidden', behind);
            el.classList.toggle('is-hover', station.hover > 0.5);
        }
    }

    function dispose() {
        items.forEach(({ el }) => el.remove());
    }

    return { update, dispose };
}
