<?php
class CF_Popup_Form {

	/**
	 * Form config
	 *
	 * @var array
	 */
	protected $form;

	/**
	 * Options
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * CF_Popup_Form constructor.
	 *
	 * @param array $form Form config
	 * @param array $options Optional config options
	 */
	public function __construct(array $form, array  $options = array()) {
		$this->form = $form;
		$this->set_options( $options );
		add_action( 'wp_footer', array( $this, 'footer' ), 400 );
	}



	protected function set_options( $options ){
		/**
		 * Filter options for a popup
		 *
		 * @since 0.0.2
		 *
		 * @param array $options Options to pass to JS land
		 * @param string $form_id Form ID
		 */
		$options = apply_filters( 'cf_popup_popup_options', $options, $this->form[ 'ID'] );
		$this->options = wp_parse_args(
			$options, CF_Popup_Settings::get_defaults()
		);
	}

	public function footer(){
		?>
		<script>
			( function ( $ ) {
				var cookieName = "<?php echo CF_Popup_Cookie::cookie_name( $this->form[ 'ID' ] ); ?>";
				if( 'dismissed' == Cookies.get( cookieName ) ){
					return;
				}

				function open(){
					var inst = $('[data-remodal-id=<?php echo esc_attr( $this->modal_id() ); ?>]').remodal();
					inst.open();
				}
				<?php
				if( false == $this->options[ 'exit_intent' ] ){
				?>
				setTimeout(function(){
					open();
				}, <?php echo $this->options[ 'delay' ]; ?> );
				<?php
				}else{
				?>
				var _ouibounce = ouibounce(false, {
					aggressive: true,
					sitewide: true,
					cookieDomain: '<?php echo COOKIE_DOMAIN; ?>',
					timer: 0,
					cookieExpire: 0,
					callback: function() { open(); }
				});

				<?php
				}

				?>




				$(document).on('closing', '#<?php echo esc_attr( $this->modal_id_attr() ); ?>', function (e) {
					Cookies.set( cookieName, 'dismissed', { expires: 7 } );

				});
			})( jQuery )


		</script>
		<div class="remodal" data-remodal-id="<?php echo esc_attr( $this->modal_id() ); ?>" data-remodal-options="hashTracking: false, closeOnOutsideClick: true" id="<?php echo esc_attr( $this->modal_id_attr() ); ?>">
			<button data-remodal-action="close" class="remodal-close"></button>
			<?php
			echo $this->options[ 'before' ];
			echo Caldera_Forms::render_form( $this->form );
			echo $this->options[ 'after' ];
			?>
		</div>
		<?php
	}

	protected function modal_id(){
		return 'cf_popup_modal_' . $this->form[ 'ID' ];
	}

	protected function modal_id_attr(){
		return $this->modal_id() . '_modal';
	}

}