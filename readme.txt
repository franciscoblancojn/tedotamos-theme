=== 🧰 TeDotamos ===
Contributors: tedotamos
Tags: woocommerce, e-commerce, marketplace, b2b, elementor
Requires at least: 6.1
Tested up to: 7.1
Requires PHP: 7.0
Stable tag: 1.1.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

🏭 Tema propio de TeDotamos.com: tienda B2B de dotación y seguridad industrial, con precios de fábrica para empresas y negocios.

== 📌 Descripción ==

TeDotamos es el tema oficial de https://tedotamos.com.

Es una copia independiente (fork) de Martfury 3.4.4 (DrFuri), adaptada y mantenida por el equipo de TeDotamos.

* 🔒 No recibe actualizaciones de Martfury: los cambios propios no se pierden.
* 🔄 Se actualiza desde nuestro repositorio de GitHub.
* 🛒 Compatible con WooCommerce, Elementor y los plugins actuales del sitio.

== ✨ Funcionalidades propias ==

* 🔄 Actualizaciones desde GitHub Releases
  * Las versiones nuevas se ven en Apariencia → TeDotamos, en Escritorio → Actualizaciones y en Apariencia → Temas.
  * Se instalan con el actualizador estándar de WordPress.
* ⚙️ Página de administración en Apariencia → TeDotamos, con cuatro pestañas:
  * Actualizaciones
  * General
  * Migración
  * Información
* 📦 Migración desde Martfury
  * Al activarse, copia la configuración del Customizer de Martfury: header, logo, colores, menús y CSS adicional.
  * Guarda una copia de lo anterior para poder restaurarlo.
* 🎨 Personalizaciones del sitio integradas directamente en el tema:
  * CSS propio (`css/tedotamos.css`);
  * botón "Pagar ahora" en productos simples y variables;
  * carrito ajustado.
  Reemplaza al plugin "Martfury Custom".
* 🛡️ Nunca consulta WordPress.org por este tema, así evita que lo reemplace otro tema con el mismo nombre.

== 🚀 Instalación ==

1. Descarga `tedotamos.zip` de la última release: https://github.com/franciscoblancojn/tedotamos-theme/releases
2. Ve a Apariencia → Temas → Añadir nuevo tema → Subir tema.
3. Antes de activarlo, haz un backup del sitio.
4. Activa TeDotamos. La configuración de Martfury se copia sola.
   Si hiciera falta, ve a Apariencia → TeDotamos → Migración → "Copiar configuración desde Martfury".
5. Purga la caché de LiteSpeed y regenera el CSS de Elementor (Elementor → Herramientas).

↩️ Para volver atrás, reactiva Martfury: su configuración no se modifica.

== 🔄 Actualizaciones ==

* En el sitio: Apariencia → TeDotamos → "Buscar actualizaciones" → "Actualizar a X.Y.Z".
* Para publicar una versión (desarrollo):

`npm run push-v -- patch`   (o minor | major)

El comando:

* sube la versión;
* hace commit y crea el tag;
* hace push.

GitHub Actions crea la release con `tedotamos.zip`.

* 🔑 Token de GitHub: no hace falta, porque el repositorio es público. Si alguna vez se necesita, va en `wp-config.php`:
  `define( 'TEDOTAMOS_GITHUB_TOKEN', '...' );`

== 🧑‍💻 Desarrollo ==

* 🎨 Las personalizaciones se hacen directo en los archivos del tema, marcadas con `TeDotamos:`. El CSS propio va en `css/tedotamos.css` y la lógica nueva en `inc/tedotamos/`. El resto es el código original de Martfury.
* 🏷️ Mantener el text domain `martfury` y los nombres `martfury_*`: las traducciones, el plugin Martfury Addons y la base de datos dependen de ellos.
* 📝 Más detalles en `TEDOTAMOS.md`.

== 📋 Registro de cambios ==

= 1.2.0 =
* 🎨 Nuevo: integra en el tema las personalizaciones del plugin "Martfury Custom" 1.0.37:
  * CSS en `css/tedotamos.css`;
  * botón "Pagar ahora";
  * carrito.
* 🔌 Si el plugin sigue activo, el tema lo neutraliza y muestra un aviso para borrarlo.

= 1.1.3 =
* 📦 Nueva publicación sin cambios de código.

= 1.1.2 =
* 📦 Nuevo: copia automática de la configuración del Customizer de Martfury al activar el tema.
* ⚙️ Nuevo: pestaña "Migración" con los botones "Copiar configuración desde Martfury" y "Restaurar copia anterior".
* 🧹 Limpia la caché de estilos del tema y purga LiteSpeed después de la copia.

= 1.1.1 =
* 🚀 Nuevo: comando `npm run push-v` para publicar versiones.
* 🏷️ El workflow de releases acepta tags `X.Y.Z` y `vX.Y.Z`.

= 1.1.0 =
* 🔄 Nuevo: actualizaciones desde GitHub Releases (Update URI).
* ⚙️ Nuevo: página Apariencia → TeDotamos (Actualizaciones, General, Información).
* 🔔 Aviso en el escritorio cuando hay una versión nueva.

= 1.0.0 =
* 🎉 Primera versión: copia independiente de Martfury 3.4.4.

== 🙏 Créditos ==

* Basado en Martfury de DrFuri: https://wpmartfury.com
* Bootstrap, Slick, PhotoSwipe, Linearicons, Ionicons, Eleganticons y Font Awesome, bajo sus respectivas licencias.
