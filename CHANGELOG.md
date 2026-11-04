# 📋 Registro de cambios

Todos los cambios importantes del tema TeDotamos. Formato basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/).

> ✍️ Antes de publicar, agrega arriba una sección `## [Sin publicar]` con los cambios. `npm run push-v -- patch|minor|major` la renombra a `## [X.Y.Z] - fecha`, y no publica si esa sección no existe.

## [1.4.12] - 2026-11-03

### 🔁 Cambiado
- 🖼️ **Imágenes del slider de la portada con editor**: en Plantilla V1 → Portada · Hero → "Slider de fondo: imágenes" ahora se ven las miniaturas; "Añadir imágenes" abre la biblioteca de medios (se pueden elegir varias), se ordenan arrastrándolas, se quitan con la ✕ y "Restaurar las del tema" vuelve a las 2 que trae el tema (las que hay por defecto). Si se quitan todas, se usan las del tema. Antes había que pegar las URL a mano, una por línea (lo guardado así se sigue leyendo).

## [1.4.11] - 2026-11-03

### ✨ Añadido
- 🖼️ **Banner de la portada con slider de fondo**: las imágenes se alternan con un fundido cada 6 segundos (configurable) y se pueden elegir con los puntos de abajo. Lleva un degradado oscuro a la izquierda para que el texto se lea. Por defecto usa las imágenes del tema (`images/hero/`, en WebP); se pueden cambiar en Plantilla V1 → Portada · Hero → "Slider de fondo: imágenes" (una URL por línea). No avanza solo si el visitante pidió reducir el movimiento y se pausa con la pestaña oculta.

### 🔁 Cambiado
- 🖼️ El banner de la portada es más alto: 560 px en computador (antes 440) y 520 px en celular.
- 🖼️ "Imagen de fondo" del hero pasa a ser solo el fondo del encabezado de la página de categorías (la portada usa el slider).

## [1.4.10] - 2026-11-03

### ✨ Añadido
- 🖥️ **Desplegable "Categorías" del header en computador**: ahora muestra **todas las categorías principales** con su ícono (antes, solo las 5 del menú "Comprar por departamento", sin Ofertas). Al pasar el ratón por una categoría se abre a la derecha un panel con sus subcategorías y "Ver todo"; al final, "Ver todas las categorías". Se puede desactivar en Plantilla V1 → Menú de categorías.

## [1.4.9] - 2026-11-03

### 🐛 Corregido
- 📱 El menú del botón ☰ (el que aparece en el computador con la ventana estrecha o en el modo responsive del navegador) seguía mostrando las 5 categorías del menú de Martfury sin íconos. Ahora muestra la misma lista que el panel "Categoría" del celular: todas las categorías con su ícono y sus subcategorías.

## [1.4.8] - 2026-11-03

### ✨ Añadido
- 📱 **Panel "Categoría" del menú inferior en el celular**: ahora muestra **todas las categorías principales** (las mismas y en el mismo orden que la página de categorías de la tienda), cada una con su **ícono**. Al tocar una categoría se despliegan sus subcategorías, con "Ver todo en …" para ir a la categoría completa, y al final "Ver todas las categorías". El título del panel pasa a ser "Categorías" y enlaza a esa página. Se configura en Plantilla V1 → Menú móvil · Categorías.
- 🧩 **Ícono de cada categoría**: se elige solo según el nombre (calzado → bota, dotación → camisa, seguridad → casco, impermeables → paraguas, emergencia → botiquín, ofertas → etiqueta). Se puede cambiar a mano en Productos → Categorías → Editar → "Ícono (TeDotamos)", y la lista de categorías muestra una columna "Ícono".

### 🔁 Cambiado
- 🛒 **Botones de compra de la ficha del producto** (simples y variables, en computador y celular), en este orden: **cantidad, favorito y comparar** en la misma línea; debajo **Añadir al carrito**; debajo **Pagar ahora** (en oscuro, para distinguirlo); y debajo **Comprar por WhatsApp**, todos a lo ancho. Antes, en computador, favorito y comparar se salían por la derecha y los botones quedaban desalineados.

## [1.4.7] - 2026-10-30

### 🐛 Corregido
- 🏷️ Tienda, categorías y ficha del producto: cuando un producto tenía dos etiquetas ("Nuevo" u "Oferta" + "+10%"), se tapaban porque Martfury pone cada una en la misma esquina. Ahora se apilan una debajo de otra.

## [1.4.6] - 2026-10-30

### ✨ Añadido
- 🏷️ **Etiqueta extra de Ofertas**: si el producto está en una subcategoría de "Ofertas" (p. ej. "10%" o "5%"), muestra una etiqueta oscura **+10%** / **+5%** junto a la de oferta, en la portada, la tienda, las categorías y los productos relacionados. El texto sale del nombre de la subcategoría ("10%" → "+10%"). Otros temas o plugins pueden añadir etiquetas con el filtro `tedotamos_product_ribbons`.

### 🔁 Cambiado
- 🧩 Portada: se quita el beneficio "Ventas corporativas · Soluciones a la medida de tu empresa" (solo cambia el valor por defecto; si editaste la lista, quítalo en Plantilla V1 → Beneficios).
- 🏷️ Tienda V1: la etiqueta de oferta pasa de círculo a pastilla, igual que la nueva "+10%".

## [1.4.5] - 2026-10-30

### 🐛 Corregido
- 📱 Cabeceras de sección de la portada ("Categorías principales", "Ofertas", "Productos destacados"): en móvil, si el título y "Ver todos…" no caben juntos, el enlace baja a la línea de abajo. Antes apretaba el título en dos líneas y se salía del margen.

## [1.4.4] - 2026-10-30

### ✨ Añadido
- 📱 **Ancho del logo en móvil** (160 px por defecto) en Plantilla V1.

### 🔁 Cambiado
- 🗂️ Portada: "Categorías principales" en 3 columnas (6 categorías = 3 + 3) y fotos en 16:9. En tablet y móvil, 2 columnas.

### 🐛 Corregido
- 🗂️ La página de categorías (y la portada sin categorías marcadas) ocultaba **"Dotación y Vestuario"**: es la categoría por defecto de WooCommerce renombrada. Ahora solo se oculta la categoría por defecto si de verdad es "Sin categorizar".
- 📱 En móvil se veía el logo móvil del Customizer en lugar del logo de V1 (la versión móvil de Martfury registra su filtro después y lo reemplazaba).

## [1.4.3] - 2026-10-30

### 🔁 Cambiado
- 🛍️ Tienda y páginas de categoría con el estilo de la portada V1:
  - contenido centrado a 1.320 px, igual que la portada. Antes el header, las migas y el catálogo iban de borde a borde;
  - migas de pan sobre fondo blanco, con enlaces del color del texto (antes azules);
  - barra lateral, filtros y barra de "ordenar / vista" como tarjetas blancas, con títulos sin mayúsculas forzadas;
  - tarjetas de producto con la foto a altura fija, precio rebajado en naranja arriba y tachado + "IVA incluido" abajo, etiquetas "Nuevo" / "-30 %" redondeadas, marca en naranja y botón de 14 px;
  - paginación con botones redondeados.

### 🐛 Corregido
- 💲 Filtro de precio de la barra lateral: el rango se salía de la tarjeta (Martfury lo posiciona en absoluto).
- 📏 En las tarjetas de producto la foto se estiraba para igualar el alto de la fila y bajaba el precio; ahora el precio queda a la misma altura en todas.
- 📱 En móvil el contenido de la tarjeta quedaba pegado al borde (`padding-inline: 0 !important` de `css/tedotamos.css`).

## [1.4.2] - 2026-10-30

### 🔁 Cambiado
- 🧱 Página de categorías en **mosaico** (columnas CSS):
  - cada tarjeta mide lo que necesitan sus subcategorías, sin estirarse a la más alta de la fila;
  - hasta 5 columnas, que bajan solas en pantallas chicas (1 en móvil);
  - la portada mantiene la grilla de alturas iguales.

## [1.4.1] - 2026-10-30

### 🔁 Cambiado
- 🗂️ Página de categorías:
  - el encabezado se ve como el hero de la portada (fondo oscuro o imagen del hero, antetítulo, título y botones);
  - todas las tarjetas tienen la misma altura, con las subcategorías dentro de la tarjeta;
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
