<?php


/**
 * Class CF_Popup_Integration
 */
abstract  class CF_Popup_Integration {

	protected $lists_key;

	protected $subscribers_key;

	protected $api_key;

	public function __construct( $api_key ){
		$this->api_key = $api_key;
	}

	public function get_lists(){
		return get_option( $this->lists_key, array( ) );
	}

	public function get_subscribers(){
		return get_option( $this->subscribers_key, array() );
	}

	public function is_subscribed( $identifier, $field ){

	}
}