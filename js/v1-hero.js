/**
 * TeDotamos V1: slider de fondo del hero de la portada.
 *
 * Alterna las imágenes de .tdt-hero__slides con un fundido (la transición está en
 * css/templates/v1.css) cada data-interval segundos. Los puntos permiten elegir una imagen.
 * Se pausa con la pestaña oculta y no avanza solo si el usuario pidió reducir el movimiento.
 */
(function () {
	'use strict';

	function init(hero) {
		var wrap = hero.querySelector('.tdt-hero__slides');
		var slides = wrap ? wrap.querySelectorAll('.tdt-hero__slide') : [];
		var dots = hero.querySelectorAll('.tdt-hero__dot');
		if (slides.length < 2) {
			return;
		}

		var delay = Math.max(3, parseInt(wrap.getAttribute('data-interval'), 10) || 6) * 1000;
		var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		var current = 0;
		var timer = null;

		function show(index) {
			current = (index + slides.length) % slides.length;
			for (var i = 0; i < slides.length; i++) {
				var active = i === current;
				slides[i].classList.toggle('is-active', active);
				if (active) {
					slides[i].removeAttribute('loading');
				}
				if (dots[i]) {
					dots[i].classList.toggle('is-active', active);
					if (active) {
						dots[i].setAttribute('aria-current', 'true');
					} else {
						dots[i].removeAttribute('aria-current');
					}
				}
			}
			// Precarga la siguiente para que el fundido no muestre una imagen a medio cargar.
			slides[(current + 1) % slides.length].removeAttribute('loading');
		}

		function stop() {
			if (timer) {
				clearInterval(timer);
				timer = null;
			}
		}

		function start() {
			stop();
			if (!reduce && !document.hidden) {
				timer = setInterval(function () {
					show(current + 1);
				}, delay);
			}
		}

		for (var d = 0; d < dots.length; d++) {
			dots[d].addEventListener('click', (function (index) {
				return function () {
					show(index);
					start();
				};
			})(d));
		}

		document.addEventListener('visibilitychange', function () {
			if (document.hidden) {
				stop();
			} else {
				start();
			}
		});

		show(0);
		start();
	}

	function boot() {
		var heroes = document.querySelectorAll('.tdt-hero--home');
		for (var i = 0; i < heroes.length; i++) {
			init(heroes[i]);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
})();
