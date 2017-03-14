<?php
class CF_Popup_Form {

	protected $form;

	protected $options;
	public function __construct(array $form, array  $options = array()) {
		$this->form = $form;
		$this->set_options( $options );
		if ( $this->should_load() ){
			add_action( 'wp_footer', array( $this, 'footer' ), 400 );
		}
	}

	protected function should_load(){
		$load = true;
		if( CF_Popup_Cookie::is_dismissed( $this->form[ 'ID' ] ) ) {
			$load = false;
		}

		return apply_filters( 'cf_popup_load', $load, $this->form );
	}

	protected function set_options( $options ){
		$this->options = wp_parse_args(
			$options,
			array(
				'exit_intent' => false,
				'delay' => 2000,
				'before' => '',
				'after' => ''
			)
		);
	}

	public function footer(){
		?>
		<script>
			( function ( $ ) {
				var cookieName = "<?php echo CF_Popup_Cookie::cookie_name( $this->form[ 'ID' ] ); ?>";
				if( 'dismissed' == $.cookie( cookieName ) ){
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
					cookieDomain: '.hiroy.clib',
					timer: 0,
					cookieExpire: 0,
					callback: function() { open(); }
				});

				<?php
				}

				?>




				$(document).on('closing', '#<?php echo esc_attr( $this->modal_id_attr() ); ?>', function (e) {
					$.cookie( cookieName, 'dismissed', { expires: 7 } );

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