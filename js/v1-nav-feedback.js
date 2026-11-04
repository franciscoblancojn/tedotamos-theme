/**
 * TeDotamos V1: aviso de "cargando" al tocar una categoría.
 *
 * Al hacer clic en un enlace del panel de categorías del celular (.tdt-mcats), del desplegable
 * de escritorio (.tdt-dcats) o de las tarjetas de categorías (.tdt-cats), el enlace se pinta con
 * el color principal y un indicador girando (css/templates/v1.css, .is-navigating) hasta que
 * abre la página nueva. Así se nota que la página está cambiando aunque el servidor tarde.
 *
 * No actúa si el clic solo despliega subcategorías (el JS de Martfury cancela la navegación),
 * ni con Ctrl/Cmd/Shift (nueva pestaña) ni en enlaces que abren otra ventana.
 */
(function () {
	'use strict';

	var SELECTOR = '.tdt-mcats a, .tdt-dcats a, .tdt-cats a';

	function clear() {
		var on = document.querySelectorAll('.is-navigating');
		for (var i = 0; i < on.length; i++) {
			on[i].classList.remove('is-navigating');
		}
	}

	document.addEventListener('click', function (e) {
		if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) {
			return;
		}
		var link = e.target.closest ? e.target.closest(SELECTOR) : null;
		if (!link || !link.href || link.target === '_blank') {
			return;
		}
		var url = new URL(link.href, location.href);
		if (url.origin !== location.origin || (url.pathname === location.pathname && url.search === location.search && url.hash)) {
			return; // otro sitio o un ancla de esta misma página
		}
		clear();
		link.classList.add('is-navigating');
		var item = link.closest('li');
		if (item) {
			item.classList.add('is-navigating');
		}
		// Por si la navegación no llega a ocurrir (p. ej. se cancela), se quita a los 15 s.
		setTimeout(clear, 15000);
	});

	// Al volver con "Atrás", el navegador puede restaurar la página tal cual (con la clase puesta).
	window.addEventListener('pageshow', clear);
})();
