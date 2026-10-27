# Tema TeDotamos

Copia independiente (fork) de **Martfury 3.4.4** (DrFuri), que es el tema activo en
tedotamos.com. Al tener otro nombre y otra carpeta, las actualizaciones de Martfury
(Envato Market, panel de WordPress) **no** lo sobrescriben.

## Qué cambió respecto a Martfury 3.4.4

| Archivo | Cambio |
|---|---|
| `style.css` | Cabecera: `Theme Name: TeDotamos`, `Version: 1.0.0`, `Update URI: false` |
| `functions.php` | Al final: `require` de `inc/tedotamos/no-updates.php` |
| `inc/tedotamos/no-updates.php` | Excluye el tema de la consulta de actualizaciones de api.wordpress.org y borra cualquier aviso de actualización |
| `TEDOTAMOS.md` | Este archivo |

Todo lo demás es idéntico al original (`theme/martfury/` en este repo es la copia de
referencia y **no se modifica**).

### Lo que se dejó igual a propósito

- **Text Domain `martfury`**: así siguen funcionando las traducciones existentes
  (`wp-content/languages/themes/martfury-*.mo` y las de Loco Translate).
- **Funciones, hooks, meta keys y opciones `martfury_*`**: el plugin **Martfury Addons**
  (widgets de Elementor, shortcodes) y la base de datos dependen de esos nombres.
  Renombrarlos rompería el sitio.

## Reglas para modificar el tema

1. El código propio va en `inc/tedotamos/` (un archivo por funcionalidad, cargado desde
   `functions.php`), y el CSS/JS propio en archivos nuevos. Así se evita editar
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
3. Copiar las opciones del tema:
   `wp option get theme_mods_martfury --format=json | wp option update theme_mods_tedotamos --format=json`
   (o con un plugin de exportar/importar el Customizer).
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
- Módulo *Product Bought Together* (`inc/modules/product-bought-together/`): su CSS
  no se usa en ninguna página (0%) y su JS casi nada (5%), pero se carga en el 98% de las páginas.
- `fonts/` (5.4 MB): `linearicons` solo se usa al 3%.
- El sitio carga `js/scripts.js` (113 KB, sin minificar) en lugar de `js/scripts.min.js`.
- `style.css` pesa 704 KB y solo se usa el 14% en el frontend.
- Hay fuentes de Google rotas en el HTML (`fonts.gstatic.com` declarado como hoja de estilos
  y una URL inválida `fonts.googleapis.com&display=swap`); revisar si vienen del tema.
