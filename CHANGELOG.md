# 📋 Registro de cambios

Todos los cambios importantes del tema TeDotamos. Formato basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/).

> ✍️ Antes de publicar, agrega arriba una sección `## [Sin publicar]` con los cambios. `npm run push-v -- patch|minor|major` la renombra a `## [X.Y.Z] - fecha`, y no publica si esa sección no existe.

## [Sin publicar]

### 🐛 Corregido
- 🔄 Actualizaciones: la versión nueva aparece aunque la revisión de WordPress no llegue a consultar GitHub (por ejemplo, si falla la conexión con WordPress.org desde el hosting). Antes la página mostraba "Hay una versión nueva en GitHub", pero no aparecía el botón "Actualizar".

### ✨ Añadido
- 🩺 Sección "Diagnóstico de actualizaciones" en Apariencia → TeDotamos → Actualizaciones.

## [1.2.0] - 2026-10-27

### 🐛 Corregido
- 🛒 Mini carrito del header y panel móvil:
  - las miniaturas tienen ancho fijo y el texto de los productos queda alineado;
  - los botones "Ver carrito" y "Finalizar compra" van a todo el ancho, uno debajo del otro, con el texto centrado.

## [1.1.5] - 2026-10-27

### ✨ Añadido
- 🎨 Las personalizaciones del plugin "Martfury Custom" 1.0.37 se integran directamente en el tema:
  - CSS en `css/tedotamos.css`, cargado después de WooCommerce, Elementor y los colores del Customizer;
  - botón "Pagar ahora" en productos simples y variables;
  - carrito: cupón en su propio formulario, miniaturas pequeñas y placeholder "Codigo Postal".
- 🔌 Si el plugin "Martfury Custom" sigue activo, el tema anula sus hooks y muestra un aviso para borrarlo.

### 🔁 Cambiado
- 📝 `readme.txt` pasa a `README.md`, y el historial de cambios a este `CHANGELOG.md`.

## [1.1.4] - 2026-10-27

### 🔁 Cambiado
- 📝 Readme propio de TeDotamos con iconos (antes era el de Martfury).
- 🧰 La descripción del tema en Apariencia → Temas lleva iconos.
- 🏷️ `npm run push-v` también actualiza la versión del readme.

## [1.1.3] - 2026-10-27

- 📦 Nueva publicación sin cambios de código.

## [1.1.2] - 2026-10-27

### ✨ Añadido
- 📦 Copia automática de la configuración del Customizer de Martfury al activar el tema.
- ⚙️ Pestaña "Migración" con los botones "Copiar configuración desde Martfury" y "Restaurar copia anterior".
- 🧹 Después de la copia, limpia la caché de estilos del tema y purga LiteSpeed.

## [1.1.1] - 2026-10-27

### ✨ Añadido
- 🚀 Comando `npm run push-v` para publicar versiones.

### 🔁 Cambiado
- 🏷️ El workflow de releases acepta tags `X.Y.Z` y `vX.Y.Z`.

## [1.1.0] - 2026-10-27

### ✨ Añadido
- 🔄 Actualizaciones desde GitHub Releases (Update URI).
- ⚙️ Página Apariencia → TeDotamos (Actualizaciones, General, Información).
- 🔔 Aviso en el escritorio cuando hay una versión nueva.

## [1.0.0] - 2026-10-26

### 🎉 Primera versión
- Copia independiente de Martfury 3.4.4.
