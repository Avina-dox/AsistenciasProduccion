

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/*
|--------------------------------------------------------------------------
| Modo claro / oscuro
|--------------------------------------------------------------------------
*/
window.alternarTema = function () {
    const esOscuro = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', esOscuro ? 'dark' : 'light');
};

/*
|--------------------------------------------------------------------------
| Fondo 3D de línea de producción (login / dashboard)
|--------------------------------------------------------------------------
| Se carga bajo demanda: Three.js sólo se descarga en páginas que incluyen
| <x-production-background />. El controlador queda en
| element.productionBackground y se emite "production-background:ready".
*/
const fondosProduccion = document.querySelectorAll('[data-production-background]');

if (fondosProduccion.length) {
    import('./production/background.js').then(({ initProductionBackground }) => {
        fondosProduccion.forEach((el) => {
            let data;

            try {
                data = el.dataset.production ? JSON.parse(el.dataset.production) : undefined;
            } catch {
                data = undefined;
            }

            const controller = initProductionBackground(el, {
                mode: el.dataset.mode,
                data,
                logoUrl: el.dataset.logo,
                labelsContainer: el.parentElement?.querySelector('[data-production-labels]') ?? null,
            });

            el.productionBackground = controller;
            el.dispatchEvent(new CustomEvent('production-background:ready', { bubbles: true, detail: controller }));
        });
    });
}

