import * as THREE from 'three';
import { STATUS_COLORS, STATUS_LABELS, WORKER_STATUS_COLORS, WORKER_STATUS_LABELS } from './config.js';

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

/*
| targets: estaciones y trabajadores. Cada uno expone hitbox (con
| userData.targetId), hoverTarget e interactive.
*/
export function createInteractions({ camera, targets, onChange }) {
    const raycaster = new THREE.Raycaster();
    const pointer = new THREE.Vector2();
    const hitboxes = targets.map((t) => t.hitbox);
    const byId = new Map(targets.map((t) => [t.id, t]));

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

    function setHovered(target) {
        if (hovered === target) return;

        if (hovered) hovered.hoverTarget = 0;
        hovered = target;
        if (hovered) hovered.hoverTarget = 1;
    }

    /*
    | Se llama desde el loop. Con el puntero sobre el fondo se re-evalúa cada
    | frame (los trabajadores caminan bajo el cursor); son pocas cajas.
    */
    function update() {
        if (!dirty && !active) return hovered;
        dirty = false;

        if (!active) {
            setHovered(null);

            return hovered;
        }

        raycaster.setFromCamera(pointer, camera);
        const hit = raycaster
            .intersectObjects(hitboxes, false)
            .find((h) => byId.get(h.object.userData.targetId)?.interactive !== false);
        setHovered(hit ? byId.get(hit.object.userData.targetId) : null);

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

/*
| Etiqueta del trabajador en hover (dashboard). Un solo elemento HTML
| reutilizado, anclado sobre la cabeza del trabajador.
*/
export function createWorkerTooltip(container, stationLabel) {
    if (!container) return null;

    const el = document.createElement('div');
    el.className = 'production-tooltip is-hidden';
    el.innerHTML = `
        <div class="production-tooltip__role"></div>
        <div class="production-tooltip__row">Estación: <strong class="production-tooltip__station"></strong></div>
        <div class="production-tooltip__row">
            Estado: <span class="production-tooltip__dot"></span><strong class="production-tooltip__status"></strong>
        </div>
    `;
    container.appendChild(el);

    const role = el.querySelector('.production-tooltip__role');
    const station = el.querySelector('.production-tooltip__station');
    const status = el.querySelector('.production-tooltip__status');
    const projected = new THREE.Vector3();
    let shown = null;
    let shownState = null;

    function update(worker, camera, width, height) {
        if (!worker || worker.kind !== 'worker') {
            el.classList.add('is-hidden');
            shown = null;

            return;
        }

        // El estado mostrado es el efectivo (p. ej. "En espera" si su estación está detenida).
        const state = worker.status === 'working' && worker.animation === 'idle' ? 'idle' : worker.status;

        if (shown !== worker || shownState !== state) {
            shown = worker;
            shownState = state;
            role.textContent = worker.role;
            station.textContent = stationLabel(worker.stationId);
            status.textContent = WORKER_STATUS_LABELS[state] ?? state;
            el.style.setProperty('--status-color', WORKER_STATUS_COLORS[state] ?? '#34D399');
        }

        projected.copy(worker.anchor).project(camera);
        const x = (projected.x * 0.5 + 0.5) * width;
        const y = (-projected.y * 0.5 + 0.5) * height;

        el.style.transform = `translate3d(${x.toFixed(1)}px, ${y.toFixed(1)}px, 0) translate(-50%, -100%)`;
        el.classList.toggle('is-hidden', projected.z > 1);
    }

    function dispose() {
        el.remove();
    }

    return { update, dispose };
}
