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
	 * Gets defaults
	 *
	 * @since 0.0.3
	 *
	 * @return array
	 */
	public static function get_defaults() {
		$form_defaults = array(
			'enabled' => false,
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

	/**
	 * Loads settings for a specific form's popup settings
	 *
	 * @since 0.0.3
	 *
	 * @param string $form_id ID of the form
	 *
	 * @return array
	 */
	public static function get_form( $form_id ) {

		$settings = self::get_settings();

		if ( isset($settings[ $form_id ]) ) {
			return $settings[ $form_id ];
		} else {
			return self::get_defaults();
		}

	}
}