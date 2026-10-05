

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

