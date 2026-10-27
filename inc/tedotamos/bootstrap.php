<?php
/**
 * TeDotamos: carga el código propio del tema (todo lo que no viene de Martfury).
 *
 * @package TeDotamos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/class-updater.php';
require_once __DIR__ . '/class-admin.php';
require_once __DIR__ . '/class-migration.php';

TeDotamos_Updater::init();
// Fuera de is_admin(): "after_switch_theme" puede dispararse en la primera visita al front.
TeDotamos_Migration::init();

if ( is_admin() ) {
	TeDotamos_Admin::init();
}
