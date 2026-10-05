/*
|--------------------------------------------------------------------------
| Configuración del fondo 3D de producción
|--------------------------------------------------------------------------
| Todo lo "ajustable" vive aquí: modos (login / dashboard), densidades,
| paletas por tema, estaciones de la línea y datos mock. Los demás módulos
| sólo leen de este archivo.
*/

export const STATUS_COLORS = {
    running: '#34D399',
    warning: '#FBBF24',
    stopped: '#F87171',
    maintenance: '#60A5FA',
};

export const STATUS_LABELS = {
    running: 'En operación',
    warning: 'Advertencia',
    stopped: 'Detenida',
    maintenance: 'Mantenimiento',
};

/*
| Estaciones de la línea principal, en orden de flujo (eje X).
| `type` decide qué constructor de machines.js se usa.
*/
export const STATIONS = [
    { id: 'preparation', label: 'Preparación', type: 'mixer', x: -12.5 },
    { id: 'processing', label: 'Procesamiento', type: 'oven', x: -3.5 },
    { id: 'packaging', label: 'Empaquetado', type: 'packer', x: 5.5 },
    { id: 'finished', label: 'Producto terminado', type: 'palletizer', x: 13 },
];

/*
| Geometría de la línea principal. Los productos nacen bajo la tolva de
| preparación (spawnX) y desaparecen al llegar al paletizado (despawnX).
*/
export const LINE = {
    startX: -15.5,
    endX: 15.8,
    z: 0,
    height: 0.95,
    width: 1.1,
    spawnX: -12.5,
    despawnX: 14.6,
    baseSpeed: 0.9, // unidades/segundo cuando productionData.speed === referenceSpeed
    referenceSpeed: 72, // piezas/hora "nominales" de los datos mock
};

/*
| Densidad = cantidad de elementos. Login usa "low", dashboard "medium".
| En mobile se baja un nivel automáticamente.
*/
export const DENSITY = {
    low: {
        productSpacing: 2.1,
        secondaryLine: false,
        racks: 3,
        silos: 3,
        pillars: 4,
        pipes: 2,
        robot: true,
    },
    medium: {
        productSpacing: 1.6,
        secondaryLine: true,
        racks: 5,
        silos: 4,
        pillars: 6,
        pipes: 3,
        robot: true,
    },
    high: {
        productSpacing: 1.25,
        secondaryLine: true,
        racks: 7,
        silos: 5,
        pillars: 8,
        pipes: 4,
        robot: true,
    },
};

const DENSITY_ORDER = ['low', 'medium', 'high'];

/*
| Paletas. "dark" es la del login (siempre oscuro) y la del dashboard en modo
| oscuro; "light" acompaña al dashboard en modo claro. Los acentos usan los
| colores de marca (morado #6A2C75, dorado, teal del esquema Material).
*/
export const PALETTES = {
    dark: {
        background: '#0A1119',
        floor: '#111E29',
        floorPad: '#152634',
        grid: '#2C5266',
        gridOpacity: 0.32,
        safety: '#B8932C',
        metal: '#95A5B5',
        metalDark: '#34434F',
        body: '#2B3C4C',
        panel: '#364B5E',
        accent: '#7B3A88',
        glow: '#4FD1C5',
        heat: '#FF8A3D',
        raw: '#E6D6B0',
        baked: '#CF9443',
        box: '#7B3A88',
        tape: '#D9B45A',
        pallet: '#5E4730',
        rack: '#2C4558',
        rackBeam: '#9A6A2E',
        load: '#4A3552',
        silo: '#4B5D6D',
        wall: '#0B151E',
        pipe: '#31424F',
        belt: '#141C23',
        rubber: '#1B2026',
        hemiSky: '#9FB7CC',
        hemiGround: '#0A1119',
        hemiIntensity: 1.7,
        keyColor: '#FFFFFF',
        keyIntensity: 2.6,
        rimColor: '#4FD1C5',
        rimIntensity: 0.9,
        haloOpacity: 0.1,
        labelTheme: 'dark',
    },
    light: {
        background: '#EDF0F7',
        floor: '#E3E7F0',
        floorPad: '#D9DEE9',
        grid: '#A9B3C8',
        gridOpacity: 0.4,
        safety: '#DDB04A',
        metal: '#AEB8C4',
        metalDark: '#6F7A88',
        body: '#F6F7FA',
        panel: '#D7DDE8',
        accent: '#6A2C75',
        glow: '#23A89B',
        heat: '#FF8A3D',
        raw: '#E6D6B0',
        baked: '#CF9443',
        box: '#7B3A88',
        tape: '#D9B45A',
        pallet: '#B08A5E',
        rack: '#8193A8',
        rackBeam: '#DB9A3C',
        load: '#C5B2CF',
        silo: '#CDD4DF',
        wall: '#E4E8F0',
        pipe: '#A3AFBE',
        belt: '#384049',
        rubber: '#2A2F35',
        hemiSky: '#FFFFFF',
        hemiGround: '#BFC6D4',
        hemiIntensity: 1.7,
        keyColor: '#FFFFFF',
        keyIntensity: 1.9,
        rimColor: '#B39DDB',
        rimIntensity: 0.55,
        haloOpacity: 0.16,
        labelTheme: 'light',
    },
};

/*
| Encuadre de cámara. `offsetX/offsetY` desplazan el render (fracción del
| viewport) sin cambiar la perspectiva: en login movemos la planta a la
| izquierda para dejar limpia la zona del formulario. `fitWidth` asegura
| que en pantallas angostas se vea al menos ese ancho de planta.
*/
export const MODES = {
    login: {
        density: 'low',
        cameraMovement: 'subtle',
        interactive: false,
        labels: false,
        theme: 'dark',
        scrollParallax: false,
        camera: {
            position: [-5, 9, 22],
            target: [-2.5, 0.4, -2.5],
            fov: 36,
            fitWidth: 30,
            offsetX: 0.17,
            offsetY: 0.04,
        },
        mobileCamera: {
            position: [0, 13, 24],
            target: [0, 0.5, -1],
            fov: 40,
            fitWidth: 17,
            offsetX: 0,
            offsetY: 0.3,
        },
    },
    dashboard: {
        density: 'medium',
        cameraMovement: 'subtle',
        interactive: true,
        labels: true,
        theme: 'auto',
        scrollParallax: true,
        camera: {
            position: [0, 9.5, 20],
            target: [0, 0.6, -1.5],
            fov: 38,
            fitWidth: 26,
            offsetX: 0,
            offsetY: 0,
        },
        mobileCamera: {
            position: [0, 13, 25],
            target: [0, 0.5, -1],
            fov: 40,
            fitWidth: 16,
            offsetX: 0,
            offsetY: 0.1,
        },
    },
};

/*
| Amplitud del movimiento automático de cámara.
*/
export const CAMERA_MOVEMENT = {
    none: { amplitude: 0 },
    subtle: { amplitude: 1 },
    gentle: { amplitude: 1.8 },
};

/*
| Datos mock. Laravel puede sustituirlos pasando `data` al componente Blade
| (<x-production-background :data="$productionData" />) o llamando a
| controller.setData(...) con lo que responda una API en el futuro.
*/
export const MOCK_PRODUCTION_DATA = {
    status: 'running',
    speed: 72,
    efficiency: 94,
    stations: [
        { id: 'preparation', status: 'running' },
        { id: 'processing', status: 'running' },
        { id: 'packaging', status: 'warning' },
        { id: 'finished', status: 'running' },
    ],
};

export function isMobileViewport() {
    return window.innerWidth < 768;
}

export function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

/*
| Une las opciones del usuario con los valores por defecto del modo y aplica
| los recortes de calidad para mobile.
*/
export function resolveOptions(options = {}) {
    const mode = MODES[options.mode] ? options.mode : 'login';
    const base = MODES[mode];
    const isMobile = options.isMobile ?? isMobileViewport();

    let density = options.density ?? base.density;

    if (isMobile) {
        const index = Math.max(DENSITY_ORDER.indexOf(density) - 1, 0);
        density = DENSITY_ORDER[index];
    }

    return {
        mode,
        isMobile,
        density,
        densityConfig: DENSITY[density] ?? DENSITY.low,
        cameraMovement: options.cameraMovement ?? base.cameraMovement,
        // En pantallas táctiles no hay hover: desactivamos la interacción.
        interactive: (options.interactive ?? base.interactive) && !isMobile,
        labels: (options.labels ?? base.labels) && !isMobile,
        theme: options.theme ?? base.theme,
        scrollParallax: (options.scrollParallax ?? base.scrollParallax) && !isMobile,
        camera: isMobile ? base.mobileCamera : base.camera,
        shadows: options.shadows ?? !isMobile,
        antialias: !isMobile,
        maxPixelRatio: isMobile ? 1.5 : 2,
        // 60 ó 30 para que el ritmo de frames sea parejo en monitores de 60/120 Hz.
        maxFps: options.maxFps ?? (isMobile ? 30 : 60),
        movementScale: isMobile ? 0.5 : 1,
        data: options.data ?? MOCK_PRODUCTION_DATA,
        labelsContainer: options.labelsContainer ?? null,
        logoUrl: options.logoUrl ?? null,
        tooltip: options.tooltip ?? null,
    };
}
