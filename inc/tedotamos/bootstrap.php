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

TeDotamos_Updater::init();

if ( is_admin() ) {
	TeDotamos_Admin::init();
}
