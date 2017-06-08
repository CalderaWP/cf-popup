<?php
/**
 Plugin Name: Caldera Forms Popup
 Version: 0.0.3
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


// define constants
define( 'CF_POPUP_PATH',  plugin_dir_path( __FILE__ ) );
define( 'CF_POPUP_URL',  plugin_dir_url( __FILE__ ) );
define( 'CF_POPUP_VER', '0.0.3' );
define( 'CF_POPUP_CORE',  __FILE__ );

/**
 * Load scripts
  */
function cf_popup_enqueue(){
	Caldera_Forms_Render_Assets::maybe_register();
	$styles = Caldera_Forms_Render_Assets::get_core_styles();
	wp_enqueue_script( Caldera_Forms_Render_Assets::make_slug( 'modals' ) );
	wp_enqueue_style( Caldera_Forms_Render_Assets::make_slug( 'modals' ), $styles[ 'modals' ],array(), CFCORE_VER );
	wp_enqueue_style( Caldera_Forms_Render_Assets::make_slug( 'modals-theme' ), $styles[ 'modals-theme'], array(), CFCORE_VER );
	wp_enqueue_script( 'exit-intent', '//cdnjs.cloudflare.com/ajax/libs/ouibounce/0.0.11/ouibounce.min.js' );
	wp_enqueue_script( 'js-cookies', '//cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.3/js.cookie.min.js' );
	//wp_enqueue_script( 'cf-ajax' );
	wp_enqueue_style( 'cf-field-styles' );
	wp_enqueue_script( 'cf-field' );
	wp_enqueue_script( 'cf-validator' );
	wp_enqueue_script( 'cf-validator-i18n' );

	wp_enqueue_script( 'cf-init' );
}

/**
 * Register autoloader root
 *
 * @since 0.0.1
 */
add_action( 'caldera_forms_includes_complete', function(){
	Caldera_Forms_Autoloader::add_root( 'CF_Popup', __DIR__ . '/classes' );
});

/**
 * Loads CF Popup submenu item in admin
 *
 * @since 0.0.3
 */
add_action( 'caldera_forms_includes_complete', function(){
	if( is_admin() ){
		new CF_Popup_Menu();
	}

},11);


/**
 * On submit, set cookie to dismissed
 *
 * @since 0.0.1
 */
add_action( 'caldera_forms_submit_complete', function( $form ){
	if( in_array( $form[ 'ID' ], cf_popup_forms() ) ){
		CF_Popup_Cookie::dismiss( $form[ 'ID' ] );
	}

});

/**
 * On redirect, remove extra query args
 *
 * @since 0.0.1
 */
add_filter( 'caldera_forms_redirect_url_complete', function( $url, $form ){
	if( in_array( $form[ 'ID' ], cf_popup_forms() ) ){
		$url = remove_query_arg( 'cf_su', $url );
		$url = remove_query_arg( 'cf_id', $url );
	}
	return $url;

}, 25, 2 );

/**
 * Load form
 */
add_action( 'template_redirect', function(){
	$form = apply_filters( 'cf_popup_select_form', null );
	if( ! is_array( $form ) ){
		$forms = cf_popup_forms();
		$form_id = false;
		if( ! empty( $forms ) ){
			shuffle( $forms );
			foreach ( $forms as $form_id ){
				if( ! CF_Popup_Cookie::is_dismissed( $form_id ) ){
					break;
				}
			}

			$form = Caldera_Forms_Forms::get_form( $form_id );
		}
	}

	if( is_array( $form ) ){
		add_action( 'wp_enqueue_scripts', 'cf_popup_enqueue', 50 );
		new CF_Popup_Form( $form );
	}
});
/**
 * Gets forms to use as popups
 *
 * @since 0.0.2
 *
 * @return array
 */
function cf_popup_forms(){
	$cf_popup_forms = array();
	$settings = CF_Popup_Settings::get_settings();
	foreach ( $settings as $form_id => $form_settings ) {
		if ( true == $form_settings[ 'enabled' ] ) {
			$cf_popup_forms[] = $form_id;
		}
	}
	/**
	 * Array of forms we could use
	 *
	 * @since 0.0.2
	 */
	return apply_filters( 'cf_popup_forms', $cf_popup_forms );

}

/**
 * Adds REST API routes for settings.
 *
 * @since 0.0.3
 */
add_action( 'caldera_forms_rest_api_init', function(){
	$api = new CF_Popup_API();

	$api->add_routes();

});