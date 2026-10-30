# 📋 Registro de cambios

Todos los cambios importantes del tema TeDotamos. Formato basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/).

> ✍️ Antes de publicar, agrega arriba una sección `## [Sin publicar]` con los cambios. `npm run push-v -- patch|minor|major` la renombra a `## [X.Y.Z] - fecha`, y no publica si esa sección no existe.

## [1.4.1] - 2026-10-30

### 🔁 Cambiado
- 🗂️ Página de categorías:
  - el encabezado se ve como el hero de la portada (fondo oscuro o imagen del hero, antetítulo, título y botones);
  - tarjetas en **mosaico** (columnas CSS): cada una mide lo que necesitan sus subcategorías, que van dentro de la tarjeta, sin estirarse a la más alta. Hasta 5 columnas, que bajan solas en pantallas chicas;
  - el texto de las etiquetas de subcategorías queda centrado verticalmente.

### 🐛 Corregido
- 📏 Se quitó el espacio en blanco de 90 px sobre la portada y la página de categorías (`padding-top` de `.site-content` de Martfury).
- 🏷️ Las etiquetas de subcategorías heredaban el estilo de la tarjeta (borde y diseño en columna), porque la regla `.tdt-cats a` también les aplicaba.

## [1.4.0] - 2026-10-30

### 🔁 Cambiado
- 🗂️ La página de categorías se crea dentro de la tienda: **`/tienda/categorias/`** (hija de la página de la tienda de WooCommerce). Si ya existía en `/categorias/`, en Plantilla V1 → Configuración inicial aparece el botón **"Mover a /tienda/categorias/"**.

## [1.3.1] - 2026-10-29

### ✨ Añadido
- 🎨 Estilo V1 para la tabla "Más descuento por cantidad" del plugin *Descuentos por Volumen* en la ficha del producto. Lleva encabezado oscuro con el ícono %, el descuento como etiqueta naranja, el mayor tramo resaltado y el texto de contacto al pie. El plugin no se modifica: fuera de V1 conserva su estilo propio.
- 🖼️ Logo **TeDotamos** del tema (`images/logo/tedotamos-td26.webp`, optimizado) en el header de V1. Ancho configurable y alto automático para que no se deforme; se puede reemplazar desde Plantilla V1.
- 🏷️ Sección "Nuestras marcas y fabricantes" con 12 logos incluidos en el tema (`images/brands/*.webp`):
  - 3M, Aritex, Croydon, Evacol, Robusta, Dezega, E.P.I., Positex, Sigmon, Steelpro, Zubi-Ola e Inteimper;
  - recortados y en WebP: pasaron de 428 KB a 68 KB;
  - si la marca existe en WooCommerce, el logo enlaza a sus productos.
- 📍 Cotización inteligente: campo **"Departamento de entrega"** (los 32 departamentos y Bogotá D.C.) antes de la ciudad. Se incluye en el mensaje de WhatsApp.

### 🔁 Cambiado
- 📝 Nota de la tabla de descuentos: "Categoría Ofertas: 5 – 10 % de descuento adicional".
- 📐 La sección **Ofertas** ocupa todo el ancho, como Categorías.
- 📞 En la barra de menú, el teléfono va al final (después de Ofertas y Marcas).

### 🐛 Corregido
- 🦶 Footer en V1: la ubicación, el correo y "Disponibilidad" no se leían, porque traían colores oscuros escritos en el widget. El copyright ahora es más grande y claro.

## [1.3.0] - 2026-10-28

### ✨ Añadido
- 🧭 Plantilla V1, barra de menú propia (reemplaza el menú y el "Header Bar" de Martfury):
  - "Todas las categorías", teléfono, Ofertas y Marcas;
  - enlaces configurables, con enlaces especiales `{ofertas}`, `{marcas}` y `{categorias}`.
- 🗂️ Página de categorías:
  - plantilla "Categorías (TeDotamos V1)": todas las categorías con sus subcategorías;
  - botón "Crear página Categorías" en Apariencia → TeDotamos → Plantilla V1.
- 🏷️ Sección **Ofertas** en la portada, con los productos de la categoría Ofertas y su % de descuento. "Ver todos los productos" lleva a la categoría. Incluye el botón "Crear categoría Ofertas".
- 🤖 Cotización inteligente: bot SVG animado (respeta "reducir movimiento") y botón "Cotizar por WhatsApp".
- 💬 Cada botón de WhatsApp envía un mensaje propio de su sección (editable). El número se configura una sola vez.
- ☑️ Las categorías de la portada se eligen con casillas (ninguna = todas).

### 🔁 Cambiado
- 🛒 "Comprar ahora" → **"Ir a la tienda"**. Los enlaces de la portada que iban a la tienda llevan a la página de categorías.
- 🏢 "Comprar para empresa" abre WhatsApp.
- 📊 Tabla de descuentos por volumen:
  - 1–5 → 30 %, 6–48 → 35 %, 49–200 → 40 %, más de 200 → "Contáctanos" (WhatsApp);
  - nota "Categoría Ofertas: 4 % de descuento adicional";
  - columnas configurables.
- ⭐ "Productos destacados" muestra los más vendidos.
- 📐 Las categorías de la portada ocupan todo el ancho (antes quedaban amontonadas a la izquierda).
- 💼 "¿Necesitas una cotización?" → **"Cotización personalizada"**, con botón a WhatsApp.
- 🔄 Los valores guardados que seguían siendo los viejos por defecto pasan a los nuevos. Lo que se había editado a mano se respeta.

### 🐛 Corregido
- 📏 En las tarjetas de producto de la portada, el botón "Ver producto" ya no se sale del borde.

## [1.2.1] - 2026-10-27

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
