# Tema TeDotamos

Copia independiente (fork) de **Martfury 3.4.4** (DrFuri), que es el tema activo en
tedotamos.com. Al tener otro nombre y otra carpeta, las actualizaciones de Martfury
(Envato Market, panel de WordPress) **no** lo sobrescriben. Se actualiza solo desde
nuestro repo: https://github.com/franciscoblancojn/tedotamos-theme

## Qué cambió respecto a Martfury 3.4.4

| Archivo | Cambio |
|---|---|
| `style.css` | Cabecera: `Theme Name: TeDotamos`, `Version`, `Update URI: https://github.com/franciscoblancojn/tedotamos-theme` |
| `functions.php` | Al final: `require` de `inc/tedotamos/bootstrap.php` |
| `inc/tedotamos/bootstrap.php` | Carga el código propio |
| `inc/tedotamos/class-updater.php` | Actualizaciones desde GitHub Releases |
| `inc/tedotamos/class-migration.php` | Copia la configuración del Customizer de Martfury al activar (y botón manual) |
| `css/tedotamos.css` + `inc/frontend/header.php` | Estilos propios del sitio (antes en el plugin Martfury Custom), cargados con prioridad 20 |
| `woocommerce/cart/cart.php` | Cupón en su propio formulario, miniatura `thumbnail`, placeholder "Codigo Postal" |
| `woocommerce/single-product/add-to-cart/simple.php` | Botón "Pagar ahora" |
| `woocommerce/single-product/add-to-cart/variation-add-to-cart-button.php` | Nuevo: botón "Pagar ahora" en productos variables |
| `inc/tedotamos/class-legacy-plugins.php` | Anula el plugin Martfury Custom si sigue activo |
| `template-parts/headers/layout-1.php` | Gancho `tedotamos_header_nav`: una plantilla (V1) puede reemplazar el menú y el Header Bar |
| `templates/v1/` + `css/templates/v1.css` + `inc/tedotamos/class-template-v1.php` | Plantilla V1: portada, página de categorías, barra de menú, estilos |
| `inc/mobile/layout.php` | Gancho `tedotamos_mobile_category_menu`: una plantilla (V1) puede reemplazar la lista del panel "Categoría" del menú inferior en móvil |
| `inc/frontend/footer.php` | Mismo gancho `tedotamos_mobile_category_menu` en el panel del botón ☰ (`#primary-mobile-nav`) |
| `inc/functions/header.php` | Filtro `tedotamos_department_menu` en `martfury_extra_department()`: una plantilla (V1) puede reemplazar la lista del desplegable "Categorías" del header |
| `inc/tedotamos/class-category-icons.php` | Ícono de cada categoría de producto (automático según el nombre o elegido a mano, term meta `tedotamos_icon`) |
| `js/v1-hero.js` + `images/hero/` | Slider de fondo del banner de la portada (V1) |
| `inc/tedotamos/class-translations.php` | Traduce al español las cadenas que salen en inglés (solo si no tienen traducción) |
| `woocommerce/myaccount/form-login.php` | Botón y pestaña de acceso: "Ingresar" (la traducción de "Log in" es "Mi Cuenta", que usa el header) |
| `css/templates/v1-fonts.css` + `fonts/inter/` | Fuente Inter alojada en el tema (LiteSpeed elimina Google Fonts) |
| `js/v1-topbar.js` | Barra superior de V1 como slider en móvil |
| `js/v1-nav-feedback.js` | Aviso de carga (color + indicador) al tocar una categoría |
| `inc/frontend/woocommerce.php` | `get_product_single_excerpt()`: unas "características" sin contenido visible ya no ocultan la descripción corta. Buscador: `instance_search_result()` guarda la caché y `product_search_sku()` no escanea `wp_postmeta` con palabras normales y limita los IDs |
| `inc/frontend/woocommerce.php` | Marca: `product_loop_brand_name()` y `single_product_brand()` muestran solo el enlace "Visita Tienda Oficial" (filtro `tedotamos_product_brand_link_text`) en lugar de "Brand:" + nombre de la marca |
| `woocommerce/loop/pagination.php` | Scroll infinito: solo el enlace a la página siguiente (y el `<nav>` vacío en la última página); expande el marcador `%_%` con el formato de paginación de WooCommerce 11 (`page/%#%/`) antes de poner el número |
| `inc/tedotamos/class-admin.php` | Página Apariencia → TeDotamos (actualizaciones + configuración general) |
| `inc/tedotamos/class-search.php` | Buscador: valida el término, cachea el desplegable, busca SKU solo si el término parece un SKU y acota los IDs. Opciones en Apariencia → TeDotamos → General |
| `js/scripts.js` | Buscador: espera 250 ms antes de consultar (antes lanzaba una petición por tecla) y cancela la pendiente al cambiar de categoría |
| `inc/frontend/header.php` | La versión de `js/scripts.js` es la del tema, para invalidar la caché del navegador al publicar |
| `inc/tedotamos/bootstrap.php` | Carga `class-search.php` |
| `inc/tedotamos/class-checkout-terms.php` | Checkout (V1): casilla obligatoria de términos y condiciones (la nativa de WooCommerce) con enlace que abre la página en otra pestaña; página y textos en Plantilla V1 → Checkout · Términos y condiciones (campo tipo `page` en `class-template-v1.php`) |
| `inc/tedotamos/class-cookie-notice.php` | Aviso de cookies (V1): barra fija abajo hasta pulsar "Aceptar" (cookie `tdt_cookies_ok`, 1 año); título, descripción, botones y página de la política en Plantilla V1 → Aviso de cookies. HTML siempre oculto y script en línea `data-no-optimize`/`data-no-defer` por la caché y el JS retrasado de LiteSpeed |
| `css/icons.css` + `fonts/subset/` + `inc/frontend/header.php` | Íconos recortados: una hoja con Linearicons/Ionicons/ElegantIcons reducidas a los íconos usados (lista en `fonts/subset/icons.json`, se regenera con `npm run build:icons` desde el repo de herramientas). Los handles `linearicons`/`ionicons`/`eleganticons` siguen registrados (los dos últimos como alias). Opción «Íconos: completos» en Apariencia → TeDotamos → General |
| `style.css` | Sin los estilos de plugins no instalados (Dokan, WCFM, WC Vendors, WCMp, WPBakery, YITH, WPML, Advanced Woo Search) ni de los layouts no usados (header ≠ 1, producto ≠ 6, catálogo ≠ 10). Hecho con `npm run purge:theme-css`; el original está en `theme/martfury/style.css` |
| `inc/modules/product-bought-together/frontend.php` | El CSS del módulo solo se encola en `is_singular( 'product' )` |
| `js/scripts.js` (`productThumbnail`) + `css/tedotamos.css` | Galería del producto: miniaturas en slider horizontal debajo de la imagen (antes vertical a la izquierda), 5 visibles; se inicia aunque FlexSlider haya creado las miniaturas antes (JS retrasado por LiteSpeed). CSS en el bloque «galería de la ficha del producto», solo `body:not(.mobile-version)` |
| `.github/workflows/release.yml` | Construye `tedotamos.zip` y crea la release al subir un tag |
| `TEDOTAMOS.md` | Este archivo |

Todo lo demás es idéntico al original (`theme/martfury/` en este repo es la copia de
referencia y **no se modifica**).

### Lo que se dejó igual a propósito

- **Text Domain `martfury`**: así siguen funcionando las traducciones existentes
  (`wp-content/languages/themes/martfury-*.mo` y las de Loco Translate).
- **Funciones, hooks, meta keys y opciones `martfury_*`**: el plugin **Martfury Addons**
  (widgets de Elementor, shortcodes) y la base de datos dependen de esos nombres.
  Renombrarlos rompería el sitio.

## Actualizaciones

**Cómo funciona**
- `Update URI` apunta a GitHub. WordPress (6.1+) llama al filtro
  `update_themes_github.com`, y `TeDotamos_Updater` responde con la última release
  (`/releases/latest`).
- La instalación la hace el actualizador estándar de temas de WordPress.
- El tema se excluye de la consulta a api.wordpress.org.
- **Paquete:** el asset `tedotamos.zip` de la release. Si la release no lo tiene, se usa el
  código fuente del tag; al instalar, la carpeta se renombra siempre a `tedotamos/`, porque
  si cambiara se perderían las opciones del tema.
- **Caché:** la consulta a GitHub se guarda en el transient `tedotamos_latest_release`
  (6 h por defecto, configurable).
- **Token:** opcional, solo para el límite de la API. Se define en `wp-config.php`:
  `define( 'TEDOTAMOS_GITHUB_TOKEN', '...' );`. **Nunca** va en el código del tema.
- Solo funciona mientras TeDotamos es el tema activo (o el padre del tema activo).

**Publicar una versión** (igual que en el plugin aveonline)
Antes, anotar los cambios en `CHANGELOG.md`, en la sección **Sin publicar**.

```bash
npm run push-v -- patch     # o minor | major
```
- `scripts/bump-version.sh` sube `Version` en `style.css`, cambia `## [Sin publicar]` por
  `## [X.Y.Z] - fecha` en `CHANGELOG.md` (si no existe esa sección, no publica) y llama a `npm run push-tag`.
- `push-tag` sincroniza `package.json` y el `**Versión:**` de `README.md`, hace commit "Release X.Y.Z" con todos los cambios
  pendientes, crea el tag `X.Y.Z` y hace push de `master` y del tag.
- `npm run push` solo hace `git push origin master`, sin release.
- Después, el workflow verifica que el tag coincida con `style.css`, construye
  `tedotamos.zip` (sin `scripts/` ni `package.json`) y crea la release.

**Actualizar el sitio:** Apariencia → TeDotamos → "Buscar actualizaciones" → "Actualizar a X.Y.Z".
También aparece en Escritorio → Actualizaciones y en Apariencia → Temas.

## Personalizaciones del sitio

El tema es nuestro: las personalizaciones se hacen **directamente** en sus archivos.

- En PHP, marcar cada cambio con un comentario `TeDotamos:` y anotarlo en la tabla de arriba.
- El CSS propio va en `css/tedotamos.css`. Carga después de WooCommerce, Elementor y los colores
  del Customizer.

Lo que hacía el plugin **Martfury Custom** (1.0.37) ya está integrado:

- **CSS:** en `css/tedotamos.css`.
- **"Pagar ahora":** en `simple.php` y en `variation-add-to-cart-button.php`.
- **Carrito:** las personalizaciones se aplicaron sobre el template de Martfury 3.4.4
  (WooCommerce 11), no sobre la copia vieja del plugin (WooCommerce 10.1).

El `variable.php` del plugin no tenía cambios propios (era una copia vieja), así que se usa el
del tema. Si el plugin sigue activo, `class-legacy-plugins.php` anula sus hooks y muestra un
aviso para borrarlo.

## Reglas para modificar el tema

1. Las personalizaciones se hacen directo en los archivos del tema, marcadas con `TeDotamos:`.
   El CSS propio va en `css/tedotamos.css` y la lógica nueva en `inc/tedotamos/` (un archivo por funcionalidad, cargado desde
   `inc/tedotamos/bootstrap.php`). Las opciones nuevas van en la pestaña General de
   `class-admin.php` (`DEFAULTS` + `sanitize()` + `render_general_tab()`), y el CSS/JS propio en archivos nuevos. Así se evita editar
   archivos del original.
2. Si hay que tocar un archivo original, marcar el cambio con un comentario
   `// TeDotamos:` y anotarlo en la tabla de arriba.
3. Subir `Version` en `style.css` en cada entrega (también sirve para invalidar caché).
4. Para traer un arreglo de una versión nueva de Martfury: descargar esa versión,
   compararla con `theme/martfury/` (3.4.4) y aplicar a mano solo lo que interese.

## Migración en producción (requiere confirmación, son escrituras en el sitio)

⚠ **Las opciones del Customizer se guardan por tema** (Kirki con `option_type: theme_mod`, en
la opción `theme_mods_martfury`). Si se activa TeDotamos sin migrarlas, el sitio pierde
el header, el footer, los colores, el logo, la asignación de menús y demás ajustes.

1. Backup completo (archivos + BD) desde hPanel.
2. Subir `dist/tedotamos-<version>.zip` en Apariencia → Temas → Añadir → Subir, **sin activarlo**.
3. Las opciones del tema se copian **solas** al activar TeDotamos por primera vez
   (`theme_mods_martfury` → `theme_mods_tedotamos`, ver `class-migration.php`). También se pueden
   copiar o restaurar a mano en Apariencia → TeDotamos → **Migración**. La copia limpia la caché
   de estilos del tema (`martfury_inline_css_*`) y purga LiteSpeed.
4. Previsualizar TeDotamos en el Customizer (vista previa en vivo, sin activarlo) y revisar
   la home, una categoría, un producto, el carrito y el checkout.
5. Activar. Después, purgar la caché de LiteSpeed y regenerar el CSS de Elementor
   (Elementor → Herramientas → Regenerar archivos).
6. Revisar menús (Apariencia → Menús → Gestionar ubicaciones) y widgets.
7. Rollback: reactivar Martfury (sus opciones siguen intactas en `theme_mods_martfury`).
8. Dejar Martfury instalado pero inactivo unas semanas; luego se puede borrar.

## Funciones del tema que el sitio no usa (para limpiar más adelante)

Según el escaneo de `data/assets-scan-latest.json` y los plugins instalados:

- Integraciones de marketplace multi-vendedor, sin plugin instalado: `dokan/`,
  `wc-vendors/`, `dc-vendors/` y `inc/vendors/` (Dokan, WC Vendors, WCMp, WCFM).
- `vc_templates/`: WPBakery no está instalado (el sitio usa Elementor).
- `woocommerce/add-to-wishlist.php`: plantilla para YITH Wishlist (el sitio usa WCBoost).
- Módulo *Product Bought Together* (`inc/modules/product-bought-together/`): su CSS ya solo
  se carga en la ficha del producto; su JS sigue cargándose en todas las páginas.
- `fonts/` (5.4 MB): las fuentes completas solo se usan con la opción «Íconos: completos».
- El sitio carga `js/scripts.js` (113 KB, sin minificar) en lugar de `js/scripts.min.js`.
- `style.css` pesa 640 KB y se usa ~20 % en el frontend: sobran sobre todo widgets de Martfury Addons que no están en ninguna página, wishlist, blog y bloques de carrito/checkout (ver `resumen/css-tema-sin-uso.md` del repo de herramientas).
- Hay fuentes de Google rotas en el HTML (`fonts.gstatic.com` declarado como hoja de estilos
  y una URL inválida `fonts.googleapis.com&display=swap`); revisar si vienen del tema.
