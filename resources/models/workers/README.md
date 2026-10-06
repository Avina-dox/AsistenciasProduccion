# Modelos 3D de trabajadores

Los trabajadores del fondo 3D (login y dashboard) usan un maniquí procedural
con el uniforme de la planta mientras no haya modelos aquí.

Para usar un modelo real, coloca archivos `.glb` en esta carpeta y ejecuta
`npm run build`:

| Archivo          | Trabajador                         |
|------------------|------------------------------------|
| `worker-01.glb`  | Operador de Preparación            |
| `worker-02.glb`  | Operador de Procesamiento          |
| `worker-03.glb`  | Operador de Empaquetado            |
| `worker-04.glb`  | Inspector de calidad (opcional)    |

Si falta el archivo de un trabajador, se reutiliza cualquier otro GLB de la
carpeta. Si no hay ninguno, se queda el maniquí.

## Requisitos del modelo

- Formato **`.glb`** (binario, todo en un archivo). Un `.gltf` con `.bin` o
  texturas externas no funcionará.
- De pie, **mirando hacia +Z**, pies en el origen. La altura se ajusta sola
  (1.70–1.80 m según el trabajador).
- Uniforme: cofia blanca, cubrebocas azul claro, orejeras azules, camiseta
  blanca de manga corta, mandil morado oscuro, pantalón blanco y calzado
  oscuro.
- Animaciones (opcionales). Se detectan por nombre: `idle`, `work`
  (o `action`/`operate`), `walk` y `break`/`rest`. Sin animaciones, el modelo
  se mueve por la planta pero sin animar el cuerpo.
- Ligero: idealmente menos de 15k triángulos y texturas de 1024 px como
  máximo. Es un fondo.
