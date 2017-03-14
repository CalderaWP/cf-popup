<?php
/**
 Plugin Name: Caldera Forms Popup
 Version: 0.0.2
 */

/**
 * Load scripts
 *
 * @todo make this conditional/ less dumb
 */
add_action( 'wp_enqueue_scripts', function(){
	Caldera_Forms_Render_Assets::maybe_register();
	$styles = Caldera_Forms_Render_Assets::get_core_styles();
	wp_enqueue_script( Caldera_Forms_Render_Assets::make_slug( 'modals' ) );
	wp_enqueue_style( Caldera_Forms_Render_Assets::make_slug( 'modals' ), $styles[ 'modals' ],array(), CFCORE_VER );
	wp_enqueue_style( Caldera_Forms_Render_Assets::make_slug( 'modals-theme' ), $styles[ 'modals-theme'], array(), CFCORE_VER );
	wp_enqueue_script( 'exit-intent', '//cdnjs.cloudflare.com/ajax/libs/ouibounce/0.0.11/ouibounce.min.js' );
	//wp_enqueue_script( 'cf-ajax' );
	wp_enqueue_style( 'cf-field-styles' );
	wp_enqueue_script( 'cf-field' );
	wp_enqueue_script( 'cf-validator' );
	wp_enqueue_script( 'cf-validator-i18n' );

	wp_enqueue_script( 'cf-init' );
}, 50 );


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
 * Gets forms to use as popups
 *
 * @return arrauy
 */
function cf_popup_forms(){
	/**
	 * Array of forms we could use
	 *
	 * @since 0.0.2
	 */
	return apply_filters( 'cf_popup_forms', array() );
}



