/**
 * TeDotamos V1: barra superior como slider en móvil.
 *
 * En pantallas de hasta 767 px muestra un elemento a la vez y pasa al siguiente cada 4 s
 * (la animación está en css/templates/v1.css). En pantallas grandes no hace nada (se ven todos).
 * Se pausa con la pestaña oculta y no avanza si el usuario pidió reducir el movimiento.
 */
(function () {
	'use strict';

	var DELAY = 4000;

	function init(list) {
		var items = list.querySelectorAll('li');
		if (items.length < 2) {
			return;
		}
		var mobile = window.matchMedia('(max-width: 767px)');
		var reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
		var current = 0;
		var timer = null;

		function next() {
			var prev = items[current];
			current = (current + 1) % items.length;
			prev.classList.remove('is-active');
			prev.classList.add('is-leaving');
			items[current].classList.remove('is-leaving');
			items[current].classList.add('is-active');
			setTimeout(function () {
				prev.classList.remove('is-leaving');
			}, 500);
		}

		function stop() {
			if (timer) {
				clearInterval(timer);
				timer = null;
			}
		}

		function update() {
			stop();
			if (mobile.matches && !reduce.matches && !document.hidden) {
				timer = setInterval(next, DELAY);
			}
		}

		document.addEventListener('visibilitychange', update);
		if (mobile.addEventListener) {
			mobile.addEventListener('change', update);
		}
		update();
	}

	function boot() {
		var lists = document.querySelectorAll('.tdt-topbar__items');
		for (var i = 0; i < lists.length; i++) {
			init(lists[i]);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
})();
