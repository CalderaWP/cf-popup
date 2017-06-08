<?php

/**
 * Class CF_Popup_Menu
 *
 * Adds the admin for CF Popup
 *
 * @package   cf-popup
 * @author    Christie Chirinos <Christie@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 CalderaWP LLC
 */
class CF_Popup_Menu {

	/**
	 * Menu slug
	 *
	 * @since 0.0.3
	 *
	 * @var string
	 */
	protected $menu_slug;

	/**
	 * CF_Popup_Menu constructor.
	 *
	 * @since 0.0.3
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_page' ) );
		$this->menu_slug = Caldera_Forms::PLUGIN_SLUG . '-cf-popup';
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
	}

	/**
	 * Add CF Popup submenu page
	 *
	 * @since 0.0.3
	 *
	 * @uses "admin_menu"
	 */
	public function add_page(){
		add_submenu_page(
			Caldera_Forms::PLUGIN_SLUG,
			__( 'Popup', 'cf-popup' ),
			__( 'Popup', 'cf-popup' ),
			Caldera_Forms::get_manage_cap(),
			$this->menu_slug,
			array( $this, 'render_admin' ) );
	}

	/**
	 * Load scripts for admin page
	 *
	 * @since 0.0.3
	 *
	 * @uses "admin_enqueue_scripts"
	 *
	 * @param $hook
	 */
	public function assets( $hook ){

		if ( isset( $_GET[ 'page' ] ) && $this->menu_slug == $_GET[ 'page' ] ) {
			Caldera_Forms_Admin_Assets::enqueue_style('admin');
			wp_enqueue_style( $this->menu_slug, CF_POPUP_URL.'/assets/admin.css' );
			wp_enqueue_script( $this->menu_slug, CF_POPUP_URL.'assets/admin.js', array('jquery'), CF_POPUP_VER );

			wp_localize_script($this->menu_slug,'CF_POPUP', array(
			      'api' => esc_url_raw( Caldera_Forms_API_Util::url( 'cf-popup/settings' ) ),
                  'nonce' => wp_create_nonce ( 'wp_rest' ),
                  'strings' => array (
                          'saved' => esc_html__( 'Settings Saved', 'cf-popup' )
                  )
            ));
		}

	}

	/**
	 * Render plugin admin page
	 *
	 * @since 0.0.3
	 *
	 * @todo move to partial and/or load via AJAX
	 */
	public function render_admin(){

		?>
		<div class="caldera-editor-header">
			<ul class="caldera-editor-header-nav">
				<li class="caldera-editor-logo">
			<span class="caldera-forms-name">
				<?php esc_html_e( 'Caldera Forms Popup', 'cf-popup' ); ?>
			</span>
				</li>
			</ul>
		</div>
		<form id="cf-popup-settings-form">
			<?php
                $settings = CF_Popup_Settings::get_settings();
				$forms = Caldera_Forms_Forms::get_forms(true);
				foreach( $forms as $form ) {
					$id = $form['ID'];
					if ( isset( $settings[ $id ] ) ) {
                        $form_settings = $settings[ $id ];
                    } else {
					    $form_settings = CF_Popup_Settings::get_defaults();
                    }
					$name = $form['name'];
					$enabled_id_attribute = 'cf-popup-enabled-setting'.$id;
					$delay_id_attribute = 'cf-popup-delay-setting-'.$id;
					$type_id_attribute = 'cf-popup-type-setting-'.$id;
					$before_id_attribute = 'cf-popup-before-setting-'.$id;
					$after_id_attribute = 'cf-popup-after-setting-'.$id;
					echo '<h2>'.$name.'</h2>';
			?>
                    <div class="cf-popup-form" data-form-id="<?php echo esc_attr($id); ?>">

                        <div class="caldera-config-group">
                            <label for="<?php echo esc_attr($enabled_id_attribute); ?>">
                                <?php esc_html_e('Enabled?', 'cf-popup'); ?>
                            </label>
                            <input type="checkbox" class="cf-popup-form-enabled" id="<?php echo esc_attr($enabled_id_attribute); ?>" <?php if ( true == (bool)$form_settings[ 'enabled' ]) { echo 'checked'; } ?> />
                        </div>

                        <div class="caldera-config-group">
                            <label for="<?php echo esc_attr($type_id_attribute); ?>">
                                <?php esc_html_e( 'Popup Type', 'cf-popup' ); ?>
                            </label>
                            <select id="<?php echo esc_attr($type_id_attribute); ?>" class="cf-popup-form-type">
                                <option value="delayed" <?php if ( false == (bool)$form_settings[ 'exit_intent' ] ) { echo 'selected'; } ?>>
                                    <?php esc_html_e('Delayed', 'cf-popup'); ?>
                                </option>
                                <option value="exit-intent" <?php if ( true == (bool)$form_settings[ 'exit_intent' ] ) { echo 'selected'; } ?>>
                                    <?php esc_html_e('Exit Intent', 'cf-popup'); ?>
                                </option>
                            </select>
                        </div>

                        <div class="caldera-config-group">
                            <label for="<?php echo esc_attr($delay_id_attribute); ?>">
                                <?php esc_html_e( 'Popup Delay Time', 'cf-popup' ); ?>
                            </label>
                            <input value="<?php echo esc_attr( $form_settings[ 'delay' ] ) ?>" class="cf-popup-form-delay" id="<?php echo esc_attr($delay_id_attribute); ?>" type="number" min="0" step="100" aria-describedby="<?php echo esc_attr($delay_id_attribute.'-description'); ?>"/>
                            <p class="description" id="<?php echo esc_attr($delay_id_attribute.'-description'); ?>">
                                <?php esc_html_e( 'Enter time in milliseconds.', 'cf-popup'); ?>
                            </p>
                        </div>

                        <div class="caldera-config-group">
                            <label for="<?php echo esc_attr($before_id_attribute); ?>">
                                <?php esc_html_e( 'Text Above Form', 'cf-popup' ); ?>
                            </label>
                            <textarea class="cf-popup-form-before" id="<?php echo esc_attr($before_id_attribute); ?>" aria-describedby="<?php echo esc_attr($before_id_attribute.'-description'); ?>">
                                <?php echo $form_settings[ 'before' ]; ?>
                            </textarea>
                            <p class="description" id="<?php echo esc_attr($before_id_attribute.'-description'); ?>">
                                <?php esc_html_e( 'Enter the text to display above your form in your popup.', 'cf-popup'); ?>
                            </p>
                        </div>

                        <div class="caldera-config-group">
                            <label for="<?php echo esc_attr($after_id_attribute); ?>">
                                <?php esc_html_e( 'Text Below Form', 'cf-popup' ); ?>
                            </label>
                            <textarea class="cf-popup-form-after" id="<?php echo esc_attr($after_id_attribute); ?>" aria-describedby="<?php echo esc_attr($after_id_attribute.'-description'); ?>">
                                <?php echo $form_settings[ 'after' ]; ?>
                            </textarea>
                            <p class="description" id="<?php echo esc_attr($after_id_attribute.'-description'); ?>">
                                <?php esc_html_e( 'Enter the text to display below your form in your popup.', 'cf-popup'); ?>
                            </p>
                        </div>

                    </div>


					<?php
				}
			submit_button( __('Save Settings','cf-popup') );
			?>


		</form>

		<?php
	}

}
