# 🧰 TeDotamos

> 🏭 Tema propio de **[TeDotamos.com](https://tedotamos.com)**: tienda B2B de dotación y seguridad industrial, con precios de fábrica para empresas y negocios.

**Versión:** 1.4.3 · **WordPress:** 6.1+ (probado hasta 7.1) · **PHP:** 7.0+ · **Licencia:** GPLv2 o posterior

📋 [Registro de cambios](CHANGELOG.md) · 📝 [Notas técnicas](TEDOTAMOS.md) · 📦 [Releases](https://github.com/franciscoblancojn/tedotamos-theme/releases)

---

## 📌 Descripción

TeDotamos es el tema oficial de https://tedotamos.com. Es una copia independiente (*fork*) de **Martfury 3.4.4** (DrFuri), adaptada y mantenida por el equipo de TeDotamos.

- 🔒 **No recibe actualizaciones de Martfury**: los cambios propios no se pierden.
- 🔄 **Se actualiza desde nuestro repositorio de GitHub.**
- 🛒 **Compatible** con WooCommerce, Elementor y los plugins actuales del sitio.

## ✨ Funcionalidades propias

| | Funcionalidad | Detalle |
|---|---|---|
| 🔄 | **Actualizaciones desde GitHub Releases** | Se ven en Apariencia → TeDotamos, Escritorio → Actualizaciones y Apariencia → Temas. Se instalan con el actualizador estándar de WordPress. |
| ⚙️ | **Página Apariencia → TeDotamos** | Pestañas Actualizaciones, General, Migración e Información. |
| 📦 | **Migración desde Martfury** | Al activarse, copia la configuración del Customizer: header, logo, colores, menús y CSS adicional. Guarda una copia de lo anterior para poder restaurarlo. |
| 🎨 | **Personalizaciones del sitio** | CSS propio (`css/tedotamos.css`), botón "Pagar ahora" en productos simples y variables, y carrito ajustado. Reemplaza al plugin "Martfury Custom". |
| 🛡️ | **Sin choques con WordPress.org** | Nunca consulta WordPress.org por este tema, así evita que lo reemplace otro tema con el mismo nombre. |

## 🚀 Instalación

1. Descarga `tedotamos.zip` de la [última release](https://github.com/franciscoblancojn/tedotamos-theme/releases/latest).
2. Ve a **Apariencia → Temas → Añadir nuevo tema → Subir tema**.
3. Antes de activarlo, haz un **backup** del sitio.
4. Activa TeDotamos. La configuración de Martfury se copia sola. Si hiciera falta, ve a **Apariencia → TeDotamos → Migración → "Copiar configuración desde Martfury"**.
5. Purga la caché de LiteSpeed y regenera el CSS de Elementor (Elementor → Herramientas).

> ↩️ Para volver atrás, reactiva Martfury: su configuración no se modifica.

## 🔄 Actualizaciones

**En el sitio:** Apariencia → TeDotamos → **Buscar actualizaciones** → **Actualizar a X.Y.Z**.

**Publicar una versión (desarrollo):**

1. Anota los cambios en [`CHANGELOG.md`](CHANGELOG.md), en una sección `## [Sin publicar]` arriba de todo.
2. Ejecuta:

   ```bash
   npm run push-v -- patch   # o minor | major
   ```

El comando:

- sube la versión en `style.css`, `package.json` y este README;
- cambia `## [Sin publicar]` del changelog por `## [X.Y.Z] - fecha` (sin esa sección no publica);
- hace commit y crea el tag;
- hace push.

GitHub Actions crea la release con `tedotamos.zip`.


## 🧑‍💻 Desarrollo

- 🎨 Las personalizaciones se hacen **directo en los archivos del tema**, marcadas con un comentario `TeDotamos:`.
  - El CSS propio va en `css/tedotamos.css`.
  - La lógica nueva va en `inc/tedotamos/`.
  - El resto es el código original de Martfury.
- 🏷️ Mantener el text domain `martfury` y los nombres `martfury_*`: las traducciones, el plugin Martfury Addons y la base de datos dependen de ellos.
- 📝 Más detalles en [`TEDOTAMOS.md`](TEDOTAMOS.md).

## 🙏 Créditos

- Basado en [Martfury](https://wpmartfury.com) de DrFuri.
- Bootstrap, Slick, PhotoSwipe, Linearicons, Ionicons, Eleganticons y Font Awesome, bajo sus respectivas licencias.
