<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * Instant Support Buttons  settings class
 *
*/
class FCB_Settings
{
    private $fcb_settings;

    /**
     * Constructor for FCB_Settings class
     */
    function __construct()
    {
        $this->fcb_settings = new FCB_Admin_Settings;
        add_action('admin_init', array($this, 'fcb_admin_section'));
        add_action('admin_menu', array($this, 'fcb_admin_menu'));
    }
  

    /**
     * Callback function for admin section
     */
	function fcb_admin_section()
    {
        //set the settings
        $this->fcb_settings->set_sections($this->fcb_get_settings_sections());
        $this->fcb_settings->set_fields($this->fcb_get_settings_fields());

        //initialize settings
        $this->fcb_settings->admin_init();
    }

    /**
     * Callback function for admin menu
     */
    function fcb_admin_menu()
    {
        add_options_page('Floating Chat Buttons', 'Floating Chat Buttons', 'manage_options', 'instant_support_buttons', array($this, 'fcb_plugin_page'));
    }


    /**
     * Get settings sections
     *
     * @return array Settings sections
     */
    function fcb_get_settings_sections()
    {
        $sections = array(
            array(
                'id' => 'fcb_basic_settings',
                'title' => __('General Settings', 'floating-contact-buttons')
            ),
            array(
                'id' => 'fcb_style_settings',
                'title' => __('Style Settings', 'floating-contact-buttons')
            ),
           
        );
        return $sections;
    }
	

    /**
     * Get settings fields
     *
     * @return array Settings fields
     */
    function fcb_get_settings_fields()
    {
		
        $settings_fields = array(
            'fcb_basic_settings' => array(
                array(
                    'name' => 'fcb_whatsapp',
                    'label' => __('WhatsApp Number', 'floating-contact-buttons'),
                    'placeholder' => __('+91XXXXXXXXXX', 'floating-contact-buttons'),
                    'desc' => __('Required','floating-contact-buttons') .' <strong>'.__('Country Code','floating-contact-buttons').'</strong> '.__('(Ex: +91XXXXXXXXXX)','floating-contact-buttons'),                                
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
				array(
                    'name' => 'fcb_facebook',
                    'label' => __('Facebook Username', 'floating-contact-buttons'),
                    'placeholder' => __('Enter Your Facebook Username','floating-contact-buttons'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
				 array(
                    'name' => 'fcb_viber',
                    'label' => __('Viber Address', 'floating-contact-buttons'),
                    'placeholder' => __('Enter Your Viber Address','floating-contact-buttons'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
				array(
                    'name' => 'fcb_slack',
                    'label' => __('Slack Team ID', 'floating-contact-buttons'),
                    'placeholder' => __('Enter Your Slack Team ID', 'floating-contact-buttons'),
                    'desc' => __('Required for','floating-contact-buttons').' <strong>'.__('Slack','floating-contact-buttons').'</strong> '.__('Support Button','floating-contact-buttons'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
				array(
                    'name' => 'fcb_slack_user',
                    'label' => __('Slack User ID', 'floating-contact-buttons'),
                    'placeholder' => __('Enter Your Slack User ID', 'floating-contact-buttons'),
                    'desc' => __('Required for','floating-contact-buttons').' <strong>'.__('Slack','floating-contact-buttons').'</strong> '.__('Support Button','floating-contact-buttons'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name' => 'fcb_twitter',
                    'label' => __('X (Twitter) Username', 'floating-contact-buttons'),
                    'placeholder' => __('Enter Your X Username', 'floating-contact-buttons'),
                    'desc' => __('"@" symbol is not required.','floating-contact-buttons'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
				array(
                    'name' => 'fcb_telegram',
                    'label' => __('Telegram Username', 'floating-contact-buttons'),
                    'placeholder' => __('Enter Your Telegram Username', 'floating-contact-buttons'),
                    'desc' => __('"@" symbol is not required.','floating-contact-buttons'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name' => 'fcb_instagram',
                    'label' => __('Instagram Username', 'floating-contact-buttons'),
                    'placeholder' => __('Enter Your Instagram Username', 'floating-contact-buttons'),
                    'desc' => __('"@" symbol is not required.','floating-contact-buttons'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
				array(
                    'name' => 'fcb_skype',
                    'label' => __('Skype Username', 'floating-contact-buttons'),
                    'placeholder' => __('Enter Your Skype Username', 'floating-contact-buttons'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name' => 'fcb_call',
                    'label' => __('Phone Number (for call now button)', 'floating-contact-buttons'),
                    'desc' => __('Required','floating-contact-buttons').' <strong>'.__('Country Code','floating-contact-buttons').'</strong> '.__('(Ex: +91XXXXXXXXXX)','floating-contact-buttons'),
                    'placeholder' => __('+91XXXXXXXXXX', 'floating-contact-buttons'),
                    'type' => 'text',
                    'sanitize_callback' => array($this, 'fcb_sanitize_phone'),
                ),
                array(
                    'name' => 'fcb_hide_call_now',
                    'label' => __(' Disable Call Now Button','floating-contact-buttons'),
                    'desc' => __('Choose the screen where you do not wish to showcase the "Call Now" button.', 'floating-contact-buttons'),
                    'type' => 'multicheck',
                    'options' => array(
                        'pc' => 'PC',
                        'mobile' => 'Mobile',
                        'tablet' => 'Tablet'
                    ),
                    'sanitize_callback' => array( $this, 'fcb_sanitize_hide_call_now' ),
                ),
				array(
                    'name' => 'fcb_email',
                    'label' => __('Email Address', 'floating-contact-buttons'),
                    'placeholder' => get_option('admin_email'),
                    'desc' => __('Required for','floating-contact-buttons').' <strong>'.__('Email Us','floating-contact-buttons').'</strong> '.__('Support Button','floating-contact-buttons'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_email'
                ),
                array(
                    'name' => 'fcb_custom_link_name',
                    'label' => __('Custom Link Label', 'floating-contact-buttons'),
                    'placeholder' => __('Enter Your Custom Link Name', 'floating-contact-buttons'),
                    'type' => 'text',
                    'desc' => __('Required for','floating-contact-buttons').' <strong>'.__('Custom Link.','floating-contact-buttons').'</strong>',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name' => 'fcb_link',
                    'label' => __('Custom Link', 'floating-contact-buttons'),
                    'placeholder' => 'Please Enter Your URL',
                    'desc' => __('"https://" is not Required.', 'floating-contact-buttons'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field',

                ),
                array(
                    'name' => 'fcb_email_to',
                    'label' => __('Email Sent To (For Callback Request):', 'floating-contact-buttons'),
                    'placeholder' => get_option('admin_email'),
                    'desc' => __('Required for','floating-contact-buttons').' <strong>'.__('Callback Request','floating-contact-buttons').'</strong> '.__('Support Button','floating-contact-buttons'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_email'
                ),
                array(
                    'name' => 'fcb_email_from',
                    'label' => __('Email From (For Callback Request):', 'floating-contact-buttons'),
                    'placeholder' => get_option('admin_email'),
                    'desc' => __('Required for','floating-contact-buttons').' <strong>'.__('Callback Request','floating-contact-buttons').'</strong> '.__('Support Button should match the one provided in the SMTP plugin settings.','floating-contact-buttons'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_email'
                ),
            ),				

            'fcb_style_settings' => array(
                array(
                    'name' => 'fcb_show_on',
                    'label' => __('Show on Pages/Posts', 'floating-contact-buttons'),
                    'desc' => __('Select where you want to display', 'floating-contact-buttons'),
                    'type' => 'radio',
                    'default' => 'all',
                    'options' => array(
                        'all' => 'ALL',
                        'custom' => 'Custom'
                    ),
                    'sanitize_callback' => array( $this, 'fcb_sanitize_show_on' ),
                ),
                array(
                    'name' => 'fcb_custom_page',
                    'label' => __('Custom Page/Post Id', 'floating-contact-buttons'),
                    'placeholder' => __('Enter page/post custom ID','floating-contact-buttons'),
                    'desc' => __('Add Page/Post Id (where you want to display floating chat buttons) with comma seperator (Ex. 2036,2251).', 'floating-contact-buttons'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field',
                    'dependency' => array(
                        'name' => 'fcb_show_on',
                        'value' => 'custom'
                    ),
                ),
                array(
                    'name' => 'fcb_font_color',
                    'label' => __('Font Color', 'floating-contact-buttons'),
                    'desc' => __('Select Font color', 'floating-contact-buttons'),
                    'type' => 'color',
                    'default' => '#12580f',
                    'sanitize_callback' => 'sanitize_hex_color'
                ),
                array(
                    'name' => 'fcb_bg_color',
                    'label' => __('Background Color', 'floating-contact-buttons'),
                    'desc' => __('Select Background color', 'floating-contact-buttons'),
                    'type' => 'color',
                    'default' => '#ffffff',
                    'sanitize_callback' => 'sanitize_hex_color'
                ),
                array(
                    'name' => 'fcb_circle_color',
                    'label' => __('Circle Color', 'floating-contact-buttons'),
                    'desc' => __('Select Circle color', 'floating-contact-buttons'),
                    'type' => 'color',
                    'default' => '#12580f',
                    'sanitize_callback' => 'sanitize_hex_color'
                ),
			),
            
        );


        return $settings_fields;
    }

    /**
     * Callback function for plugin page
     */
    function fcb_plugin_page()
    {
        echo '<div class="wrap">
        <h1>';
        echo esc_html( get_admin_page_title() );
        echo '</h1>';  
        $this->fcb_settings->show_navigation();
        $this->fcb_settings->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages()
    {
        $pages = get_pages();
        $pages_options = array();
        if ($pages) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }
	
    /**
     * Get option value
     *
     * @param string $option Option name
     * @param string $section Section name
     * @param string $default Default value
     * @return mixed Option value
     */
	function fcb_get_option($option, $section, $default = '')
	{

	  $options = get_option($section);

	  if (isset($options[$option])) {
		return $options[$option];
	  }

	  return $default;
	}

	/**
	 * Sanitize phone number
	 *
	 * @param string $phone Phone number input
	 * @return string Sanitized phone number
	 */
	function fcb_sanitize_phone($phone) {
		// Remove all non-digit characters except plus sign
		$phone = preg_replace('/[^+0-9]/', '', $phone);
		
		// Validate format: optional + followed by 7-15 digits
		if (!preg_match('/^\+?[0-9]{7,15}$/', $phone)) {
			return ''; // Return empty string for invalid phone numbers
		}
		
		return $phone;
	}

	/**
	 * Sanitize show-on radio value
	 *
	 * @param string $value Submitted radio value
	 * @return string Allowed values only: all, custom
	 */
	function fcb_sanitize_show_on( $value ) {
		return in_array( $value, array( 'all', 'custom' ), true ) ? $value : 'all';
	}

	/**
	 * Sanitize hide call now multicheck values
	 *
	 * @param mixed $value Submitted multicheck values
	 * @return array Allowed keys only: pc, mobile, tablet
	 */
	function fcb_sanitize_hide_call_now( $value ) {
        if ( ! is_array( $value ) ) {
            return array();
        }
    
        $allowed   = array( 'pc', 'mobile', 'tablet' );
        $sanitized = array();
    
        foreach ( $allowed as $key ) {
            if ( isset( $value[ $key ] ) ) {
                $sanitized[ $key ] = $key;
            }
        }
    
        return $sanitized;
    }
}