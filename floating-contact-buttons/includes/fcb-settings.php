<?php
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
        add_options_page('Floating Chat Buttons', 'Floating Chat Buttons', 'delete_posts', 'instant_support_buttons', array($this, 'fcb_plugin_page'));
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
                'title' => __('General Settings', 'fcb')
            ),
            array(
                'id' => 'fcb_style_settings',
                'title' => __('Style Settings', 'fcb')
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
                    'label' => __('WhatsApp Number', 'fcb'),
                    'placeholder' => __('+91XXXXXXXXXX', 'fcb'),
                    'desc' => __('Required','fcb') .' <strong>'.__('Country Code','fcb').'</strong> '.__('(Ex: +91XXXXXXXXXX)','fcb'),                                        
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
				array(
                    'name' => 'fcb_facebook',
                    'label' => __('Facebook Username', 'fcb'),
                    'placeholder' => __('Enter Your Facebook Username','fcb'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
				 array(
                    'name' => 'fcb_viber',
                    'label' => __('Viber Address', 'fcb'),
                    'placeholder' => __('Enter Your Viber Address','fcb'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
				array(
                    'name' => 'fcb_slack',
                    'label' => __('Slack Team ID', 'fcb'),
                    'placeholder' => __('Enter Your Slack Team ID', 'fcb'),
                    'desc' => __('Required for','fcb').' <strong>'.__('Slack','fcb').'</strong> '.__('Support Button','fcb'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
				array(
                    'name' => 'fcb_slack_user',
                    'label' => __('Slack User ID', 'fcb'),
                    'placeholder' => __('Enter Your Slack User ID', 'fcb'),
                    'desc' => __('Required for','fcb').' <strong>'.__('Slack','fcb').'</strong> '.__('Support Button','fcb'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name' => 'fcb_twitter',
                    'label' => __('X (Twitter) Username', 'fcb'),
                    'placeholder' => __('Enter Your X Username', 'fcb'),
                    'desc' => __('"@" symbol is not required.','fcb'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
				array(
                    'name' => 'fcb_telegram',
                    'label' => __('Telegram Username', 'fcb'),
                    'placeholder' => __('Enter Your Telegram Username', 'fcb'),
                    'desc' => __('"@" symbol is not required.','fcb'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name' => 'fcb_instagram',
                    'label' => __('Instagram Username', 'fcb'),
                    'placeholder' => __('Enter Your Instagram Username', 'fcb'),
                    'desc' => __('"@" symbol is not required.','fcb'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
				array(
                    'name' => 'fcb_skype',
                    'label' => __('Skype Username', 'fcb'),
                    'placeholder' => __('Enter Your Skype Username', 'fcb'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name' => 'fcb_call',
                    'label' => __('Phone Number (for call now button)', 'fcb'),
                    'desc' => __('Required','fcb').' <strong>'.__('Country Code','fcb').'</strong> '.__('(Ex: +91XXXXXXXXXX)','fcb'),
                    'placeholder' => __('+91XXXXXXXXXX', 'fcb'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'name' => 'fcb_hide_call_now',
                    'label' => __(' Disable Call Now Button','fcb'),
                    'desc' => __('Choose the screen where you do not wish to showcase the "Call Now" button.', 'fcb'),
                    'type' => 'multicheck',
                    'options' => array(
                        'pc' => 'PC',
                        'mobile' => 'Mobile',
                        'tablet' => 'Tablet'
                    )
                ),
				array(
                    'name' => 'fcb_email',
                    'label' => __('Email Address', 'fcb'),
                    'placeholder' => get_option('admin_email'),
                    'desc' => __('Required for','fcb').' <strong>'.__('Email Us','fcb').'</strong> '.__('Support Button','fcb'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name' => 'fcb_custom_link_name',
                    'label' => __('Custom Link Label', 'fcb'),
                    'placeholder' => __('Enter Your Custom Link Name', 'fcb'),
                    'type' => 'text',
                    'desc' => __('Required for','fcb').' <strong>'.__('Custom Link.','fcb').'</strong>',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name' => 'fcb_link',
                    'label' => __('Custom Link', 'fcb'),
                    'placeholder' => 'Please Enter Your URL',
                    'desc' => __('"https://" is not Required.'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'name' => 'fcb_email_to',
                    'label' => __('Email Sent To (For Callback Request):', 'fcb'),
                    'placeholder' => get_option('admin_email'),
                    'desc' => __('Required for','fcb').' <strong>'.__('Callback Request','fcb').'</strong> '.__('Support Button','fcb'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name' => 'fcb_email_from',
                    'label' => __('Email From (For Callback Request):', 'fcb'),
                    'placeholder' => get_option('admin_email'),
                    'desc' => __('Required for','fcb').' <strong>'.__('Callback Request','fcb').'</strong> '.__('Support Button should match the one provided in the SMTP plugin settings.','fcb'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
            ),				

            'fcb_style_settings' => array(
                array(
                    'name' => 'fcb_show_on',
                    'label' => __('Show on Pages/Posts', 'fcb'),
                    'desc' => __('Select where you want to display', 'fcb'),
                    'type' => 'radio',
                    'default' => 'all',
                    'options' => array(
                        'all' => 'ALL',
                        'custom' => 'Custom'
                    )
                ),
                array(
                    'name' => 'fcb_custom_page',
                    'label' => __('Custom Page/Post Id', 'fcb'),
                    'placeholder' => __('Enter page/post custom ID','fcb'),
                    'desc' => __('Add Page/Post Id (where you want to display floating chat buttons) with comma seperator (Ex. 2036,2251).', 'fcb'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field',
                    'dependency' => array(
                        'name' => 'fcb_show_on',
                        'value' => 'custom'
                    ),
                ),
                array(
                    'name' => 'fcb_font_color',
                    'label' => __('Font Color', 'fcb'),
                    'desc' => __('Select Font color', 'fcb'),
                    'type' => 'color',
                    'default' => '#12580f'
                ),
                array(
                    'name' => 'fcb_bg_color',
                    'label' => __('Background Color', 'fcb'),
                    'desc' => __('Select Background color', 'fcb'),
                    'type' => 'color',
                    'default' => '#ffffff'
                ),
                array(
                    'name' => 'fcb_circle_color',
                    'label' => __('Circle Color', 'fcb'),
                    'desc' => __('Select Circle color', 'fcb'),
                    'type' => 'color',
                    'default' => '#12580f'
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
}