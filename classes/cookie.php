<?php
class CF_Popup_Cookie {
	/**
	 * @var string
	 */
	protected  static  $COOKIE_PREFIX = '_cf_popup_';

	/**
	 * @param $form_id
	 *
	 * @return string
	 */
	public static function cookie_name( $form_id){
		return self::$COOKIE_PREFIX . $form_id;
	}

	/**
	 * Check if cookie is dismissed for form
	 *
	 * @param $form_id
	 *
	 * @return bool
	 */
	public static function is_dismissed( $form_id ){
		if( isset( $_COOKIE ) && isset( $_COOKIE[ self::cookie_name( $form_id ) ]  ) ){
			return true;
		}

		return false;
	}

	/**
	 * Dismiss for a form
	 *
	 * @param $form_id
	 */
	public static function dismiss( $form_id ){
		setcookie( self::cookie_name( $form_id ), 1, time()+86400*7, COOKIEPATH, COOKIE_DOMAIN, false);
	}
}