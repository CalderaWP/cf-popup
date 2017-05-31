<?php

/**
 * Created by PhpStorm.
 * User: christie
 * Date: 5/31/17
 * Time: 1:25 PM
 */
class CF_Popup_Settings {
	/**
	 * Option key to save settings
	 *
	 * @var string
	 */
	protected static $option_key = 'cf_popup_settings';

	/**
	 * Sets default settings
	 *
	 * @var array
	 */
	protected static $default_settings = array(

	);

	/**
	 * Gets defaults
	 *
	 *
	 *
	 * @return array
	 */
	public static function get_defaults() {
		$form_defaults = array(
			'exit_intent' => false,
			'delay' => 2000,
			'before' => '',
			'after' => ''
		);
		return $form_defaults;
	}

	/**
	 * Gets saved settings
	 *
	 * @return array
	 */
	public static function get_settings() {

		$defaults = self::get_defaults();

		$saved = get_option( self::$option_key, array() );

		if ( empty($saved) || ! is_array($saved) ) {
			return array();
		}

		foreach ( $saved as $form_id => $save ) {
			$saved[ $form_id ] = wp_parse_args( $save, $defaults );
		}

		return $saved;

	}

	/**
	 * Saves the settings
	 *
	 * @param array $settings
	 */
	public static function save_settings( array $settings ) {

		$defaults = self::get_defaults();

		foreach ( $settings as $form_id => $setting ) {
			if ( ! is_array( $setting ) ) {
				$settings[ $form_id ] = $defaults;
			} else {
				foreach ( $setting as $i => $form_setting ) {
					if ( ! array_key_exists( $i, $defaults ) ) {
						unset( $settings[ $form_id ] [ $i ] );
					}
				}
				$settings[ $form_id ] = wp_parse_args( $settings [ $form_id ], $defaults );
			}
		}

		update_option ( self::$option_key, $settings );

	}
}