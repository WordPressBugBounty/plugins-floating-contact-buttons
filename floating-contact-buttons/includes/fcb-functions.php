<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class FCB_functions{

	function __construct()
    {
	}


	function fcb_checkDevice(){
	// checkDevice() : checks if user device is phone, tablet, or desktop
	// RETURNS 0 for desktop, 1 for mobile, 2 for tablets
		if(isset($_SERVER['HTTP_USER_AGENT'])){
			$user_agent = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
			if(is_numeric(strpos(strtolower($user_agent), "mobile"))){
				return is_numeric(strpos(strtolower($user_agent), "tablet")) ? 2 : 1 ;
			}else{
				return 0;
			}
		}
	}

	function get_custom_link_name(){
		$FCB_Setting=new FCB_Settings();
		$custom_link_name = ($FCB_Setting->fcb_get_option('fcb_custom_link_name', 'fcb_basic_settings'));
		if($custom_link_name == ''){
			$custom_link_name = 'Custom Link';
		}
		return $custom_link_name;
	}

	function fcb_social_media(){
		$custom_link_name = $this->get_custom_link_name(); 
		$select_social_media= array(
			'fcb_whatsapp'		=> __('WhatsApp','floating-contact-buttons'),
			'fcb_facebook'		=> __('Facebook','floating-contact-buttons'),
			'fcb_viber'			=> __('Viber','floating-contact-buttons'),
			'fcb_slack'			=> __('Slack','floating-contact-buttons'),
			'fcb_twitter'		=> __('X (Twitter)','floating-contact-buttons'),
			'fcb_telegram'		=> __('Telegram','floating-contact-buttons'),
			'fcb_instagram'		=> __('Instagram','floating-contact-buttons'),
			'fcb_skype'			=> __('Skype','floating-contact-buttons'),
			'fcb_call'			=> __('Call Now','floating-contact-buttons'),
			'fcb_email'			=> __('Email Us','floating-contact-buttons'),
			'fcb_link'			=> $custom_link_name,
			'fcb_phone'			=> __('Callback Request','floating-contact-buttons')
		);

		return $select_social_media;
	}

}