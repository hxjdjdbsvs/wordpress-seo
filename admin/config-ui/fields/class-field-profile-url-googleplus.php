<?php
/**
 * @package WPSEO\Admin\ConfigurationUI
 */

/**
 * Class WPSEO_Config_Field_Profile_URL_GooglePlus
 */
class WPSEO_Config_Field_Profile_URL_GooglePlus extends WPSEO_Config_Field {

	/**
	 * WPSEO_Config_Field_Profile_URL_GooglePlus constructor.
	 */
	public function __construct() {
		parent::__construct( 'profileUrlGooglePlus', 'input' );

		$this->set_property( 'label', __( 'Google+ URL', 'wordpress-seo' ) );
		$this->set_property( 'pattern', '^https:\/\/plus\.google\.com\/([^/]+)$' );
	}

	/**
	 * Set adapter
	 *
	 * @param WPSEO_Configuration_Options_Adapter $adapter Adapter to register lookup on.
	 */
	public function set_adapter( WPSEO_Configuration_Options_Adapter $adapter ) {
		$adapter->add_yoast_lookup( $this->get_identifier(), 'wpseo_social', 'google_plus_url' );
	}
}
