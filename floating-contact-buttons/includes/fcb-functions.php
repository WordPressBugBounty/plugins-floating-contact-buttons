<?php
class FCB_functions{

	function __construct()
    {
	}


	function fcb_checkDevice(){
	// checkDevice() : checks if user device is phone, tablet, or desktop
	// RETURNS 0 for desktop, 1 for mobile, 2 for tablets
		if(isset($_SERVER['HTTP_USER_AGENT'])){
			if(is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "mobile"))){
				return is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "tablet")) ? 2 : 1 ;
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
			'fcb_whatsapp'		=> __('WhatsApp','fcb'),
			'fcb_facebook'		=> __('Facebook','fcb'),
			'fcb_viber'			=> __('Viber','fcb'),
			'fcb_slack'			=> __('Slack','fcb'),
			'fcb_twitter'		=> __('X (Twitter)','fcb'),
			'fcb_telegram'		=> __('Telegram','fcb'),
			'fcb_instagram'		=> __('Instagram','fcb'),
			'fcb_skype'			=> __('Skype','fcb'),
			'fcb_call'			=> __('Call Now','fcb'),
			'fcb_email'			=> __('Email Us','fcb'),
			'fcb_link'			=> $custom_link_name,
			'fcb_phone'			=> __('Callback Request','fcb')
		);

		return $select_social_media;
	}

}