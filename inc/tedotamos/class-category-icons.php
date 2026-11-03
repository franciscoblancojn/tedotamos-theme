<?php
/**
 * TeDotamos: ícono de cada categoría de producto.
 *
 * - Por defecto se elige solo, según el nombre y el slug de la categoría (RULES).
 * - Se puede elegir a mano en Productos → Categorías → Editar → "Ícono (TeDotamos)".
 *   Se guarda en el term meta `tedotamos_icon`.
 *
 * Los íconos son los SVG de TeDotamos_Template_V1::icon(). Lo usa el panel "Categoría" del
 * menú inferior en móvil (TeDotamos_Template_V1::render_mobile_categories()).
 *
 * @package TeDotamos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TeDotamos_Category_Icons {

	const META = 'tedotamos_icon';

	/** Ícono si ninguna regla coincide. */
	const FALLBACK = 'box';

	/**
	 * Ícono automático: la primera regla con alguna palabra en el nombre o el slug
	 * (en minúsculas y sin tildes). El orden importa: "Ropa impermeable" → paraguas, no camisa.
	 */
	const RULES = array(
		'tag'      => array( 'oferta', 'descuento', 'promo', 'outlet' ),
		'umbrella' => array( 'impermeab', 'lluvia' ),
		'firstaid' => array( 'emergencia', 'botiquin', 'primeros auxilios', 'brigada', 'rescate', 'camilla' ),
		'boot'     => array( 'calzado', 'bota', 'zapat', 'tenis' ),
		'glove'    => array( 'guante', 'manos' ),
		'hardhat'  => array( 'seguridad', 'epp', 'proteccion', 'casco', 'alturas' ),
		'shirt'    => array( 'dotacion', 'vestuario', 'uniforme', 'ropa', 'camis', 'pantalon', 'chaqueta', 'overol' ),
		'tool'     => array( 'herramienta', 'maquinaria', 'ferreteria' ),
	);

	public static function init() {
		add_action( 'product_cat_add_form_fields', array( __CLASS__, 'add_field' ) );
		add_action( 'product_cat_edit_form_fields', array( __CLASS__, 'edit_field' ) );
		add_action( 'created_product_cat', array( __CLASS__, 'save' ) );
		add_action( 'edited_product_cat', array( __CLASS__, 'save' ) );
		add_filter( 'manage_edit-product_cat_columns', array( __CLASS__, 'add_column' ) );
		add_filter( 'manage_product_cat_custom_column', array( __CLASS__, 'render_column' ), 10, 3 );
	}

	/**
	 * Íconos que se pueden elegir a mano.
	 */
	public static function choices() {
		return array(
			'boot'     => 'Bota (calzado)',
			'shirt'    => 'Camisa (dotación, vestuario)',
			'hardhat'  => 'Casco (seguridad industrial, EPP)',
			'glove'    => 'Guante (protección de manos)',
			'umbrella' => 'Paraguas (impermeables, lluvia)',
			'firstaid' => 'Botiquín (emergencias)',
			'tag'      => 'Etiqueta (ofertas)',
			'percent'  => 'Porcentaje',
			'tool'     => 'Herramienta',
			'shield'   => 'Escudo',
			'truck'    => 'Camión',
			'building' => 'Edificio',
			'cart'     => 'Carrito',
			'box'      => 'Caja',
		);
	}

	/**
	 * Nombre del ícono de una categoría (manual o automático).
	 */
	public static function name( WP_Term $term ) {
		$manual = get_term_meta( $term->term_id, self::META, true );
		if ( $manual && isset( self::choices()[ $manual ] ) ) {
			return $manual;
		}

		return self::auto( $term );
	}

	public static function auto( WP_Term $term ) {
		$haystack = strtolower( remove_accents( $term->name . ' ' . str_replace( '-', ' ', $term->slug ) ) );
		foreach ( self::RULES as $icon => $words ) {
			foreach ( $words as $word ) {
				if ( false !== strpos( $haystack, $word ) ) {
					return $icon;
				}
			}
		}

		return self::FALLBACK;
	}

	/**
	 * SVG del ícono de una categoría.
	 */
	public static function svg( WP_Term $term, $class = 'tdt-icon' ) {
		return TeDotamos_Template_V1::icon( self::name( $term ), $class );
	}

	// ------------------------------------------------------------------ admin

	private static function select( $current ) {
		echo '<select name="' . esc_attr( self::META ) . '" id="' . esc_attr( self::META ) . '">';
		echo '<option value="">Automático (según el nombre)</option>';
		foreach ( self::choices() as $value => $label ) {
			printf( '<option value="%s"%s>%s</option>', esc_attr( $value ), selected( $current, $value, false ), esc_html( $label ) );
		}
		echo '</select>';
	}

	public static function add_field() {
		?>
		<div class="form-field term-tedotamos-icon-wrap">
			<label for="<?php echo esc_attr( self::META ); ?>">Ícono (TeDotamos)</label>
			<?php self::select( '' ); ?>
			<p>Se muestra en el menú de categorías del celular. "Automático" lo elige según el nombre.</p>
		</div>
		<?php
	}

	public static function edit_field( $term ) {
		$current = get_term_meta( $term->term_id, self::META, true );
		?>
		<tr class="form-field term-tedotamos-icon-wrap">
			<th scope="row"><label for="<?php echo esc_attr( self::META ); ?>">Ícono (TeDotamos)</label></th>
			<td>
				<?php self::select( $current ); ?>
				<span style="display:inline-block;width:24px;height:24px;vertical-align:middle;margin-left:8px;"><?php echo self::svg( $term ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<p class="description">Se muestra en el menú de categorías del celular. "Automático" lo elige según el nombre (ahora: <?php echo esc_html( self::choices()[ self::auto( $term ) ] ?? self::auto( $term ) ); ?>).</p>
			</td>
		</tr>
		<?php
	}

	/**
	 * WordPress ya verificó el nonce del formulario de términos antes de crear/editar.
	 */
	public static function save( $term_id ) {
		if ( ! isset( $_POST[ self::META ] ) || ! current_user_can( 'manage_product_terms' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			return;
		}
		$value = sanitize_key( wp_unslash( $_POST[ self::META ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( $value && isset( self::choices()[ $value ] ) ) {
			update_term_meta( $term_id, self::META, $value );
		} else {
			delete_term_meta( $term_id, self::META );
		}
	}

	public static function add_column( $columns ) {
		$new = array();
		foreach ( $columns as $key => $label ) {
			$new[ $key ] = $label;
			if ( 'name' === $key ) {
				$new['tedotamos_icon'] = 'Ícono';
			}
		}

		return $new;
	}

	public static function render_column( $content, $column, $term_id ) {
		if ( 'tedotamos_icon' !== $column ) {
			return $content;
		}
		$term = get_term( $term_id, 'product_cat' );
		if ( ! $term instanceof WP_Term ) {
			return $content;
		}
		$manual = get_term_meta( $term_id, self::META, true );

		return '<span style="display:inline-block;width:22px;height:22px;" title="' . esc_attr( $manual ? 'Elegido a mano' : 'Automático' ) . '">' . self::svg( $term ) . '</span>';
	}
}
