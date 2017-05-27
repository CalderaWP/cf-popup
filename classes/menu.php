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
		<div id="cf-popup-forms-list">
			<?php
				$forms = Caldera_Forms_Forms::get_forms(true);
				foreach( $forms as $form ) {
					$id = $form['ID'];
					$name = $form['name'];
					$delay_id_attribute = 'cf-popup-delay-setting-'.$id;
					$type_id_attribute = 'cf-popup-type-setting-'.$id;
					$before_id_attribute = 'cf-popup-before-setting-'.$id;
					$after_id_attribute = 'cf-popup-after-setting-'.$id;
					echo '<h2>'.$name.'</h2>';
			?>
					<div class="caldera-config-group">
						<label for="<?php echo esc_attr($type_id_attribute); ?>">
							<?php esc_html_e( 'Popup Type', 'cf-popup' ); ?>
						</label>
						<select id="<?php echo esc_attr($type_id_attribute); ?>">
							<option value="disabled">
								<?php esc_html_e('Disabled', 'cf-popup'); ?>
							</option>
							<option value="delayed">
								<?php esc_html_e('Delayed', 'cf-popup'); ?>
							</option>
							<option value="exit-intent">
								<?php esc_html_e('Exit Intent', 'cf-popup'); ?>
							</option>
						</select>
					</div>

					<div class="caldera-config-group">
						<label for="<?php echo esc_attr($delay_id_attribute); ?>">
							<?php esc_html_e( 'Popup Delay Time', 'cf-popup' ); ?>
						</label>
						<input id="<?php echo esc_attr($delay_id_attribute); ?>" type="number" min="0" step="100" aria-describedby="<?php echo esc_attr($delay_id_attribute.'-description'); ?>"/>
						<p class="description" id="<?php echo esc_attr($delay_id_attribute.'-description'); ?>">
							<?php esc_html_e( 'Enter time in milliseconds.', 'cf-popup'); ?>
						</p>
					</div>

					<div class="caldera-config-group">
						<label for="<?php echo esc_attr($before_id_attribute); ?>">
							<?php esc_html_e( 'Text Above Form', 'cf-popup' ); ?>
						</label>
						<textarea id="<?php echo esc_attr($before_id_attribute); ?>" aria-describedby="<?php echo esc_attr($before_id_attribute.'-description'); ?>">

						</textarea>
						<p class="description" id="<?php echo esc_attr($before_id_attribute.'-description'); ?>">
							<?php esc_html_e( 'Enter the text to display above your form in your popup.', 'cf-popup'); ?>
						</p>
					</div>

					<div class="caldera-config-group">
						<label for="<?php echo esc_attr($after_id_attribute); ?>">
							<?php esc_html_e( 'Text Below Form', 'cf-popup' ); ?>
						</label>
						<textarea id="<?php echo esc_attr($after_id_attribute); ?>" aria-describedby="<?php echo esc_attr($after_id_attribute.'-description'); ?>">

						</textarea>
						<p class="description" id="<?php echo esc_attr($after_id_attribute.'-description'); ?>">
							<?php esc_html_e( 'Enter the text to display below your form in your popup.', 'cf-popup'); ?>
						</p>
					</div>

			<?php
				}
			submit_button( __('Save Settings','cf-popup') );
			?>


		</div>

		<?php
	}

}
