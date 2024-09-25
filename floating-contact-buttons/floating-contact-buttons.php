<?php
/**
 * Plugin Name: Instant Support Buttons - Call, Contact, Chat, Email
 * Description: Floating Chat Buttons for enhancing user engagement offer various options such as callback, Skype, Slack, Instagram, WhatsApp and Telegram.
 * Author: Cool Plugins
 * Author URI: https://coolplugins.net/
 * Plugin URI: 
 * Version: 1.2.0
 * License: GPL2
 * Text Domain: fcb
 * Domain Path: languages
 */

if (!defined('ABSPATH')) {
    exit;
}

if (defined('FCB_VERSION')) {
    return;
}

define('FCB_VERSION', '1.2.0');
define('FCB_FILE', __FILE__);
define('FCB_PATH', plugin_dir_path(FCB_FILE));
define('FCB_URL', plugin_dir_url(FCB_FILE));

register_activation_hook(FCB_FILE, array('Floating_Contact_Buttons', 'fcb_activate'));
register_deactivation_hook(FCB_FILE, array('Floating_Contact_Buttons', 'fcb_deactivate'));

/**
 * Class Floating_Contact_Buttons
 */
final class Floating_Contact_Buttons
{

    /**
     * Plugin instance.
     *
     * @var Floating_Contact_Buttons
     * @access private
     */
    private static $instance = null;

    /**
     * Get plugin instance.
     *
     * @return Floating_Contact_Buttons
     * @static
     */
    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Constructor.
     *
     * @access private
     */
    private function __construct()
    {
        $this->fcb_includes();
       	add_action('init',array($this, 'fcb_init_plugin') );
        add_action( 'plugins_loaded', array( $this, 'fcb_init' ) );
        if(is_admin()){
        add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this,'fcb_setting_panel_action_link'));
        }
    }

	/**
	* Inilialize settings and layout
	*/
	function fcb_init_plugin(){
		new FCB_Settings();
		new FCB_Layout();
	}
    /**
     * Load plugin function files here.
     */
    public function fcb_includes()
    {	
        if ( is_admin() ) {
            require_once FCB_PATH . '/feedback/admin-feedback-form.php';           
            require_once __DIR__ . "/includes/fcb-feedback-notice.php";
            new fcbFeedbackNotice();
        }
        require_once dirname(__FILE__) . '/includes/fcb-functions.php';
        require_once dirname(__FILE__) . '/includes/fcb-classes.php';
        require_once dirname(__FILE__) . '/includes/fcb-settings.php';      
		require_once dirname(__FILE__) . '/includes/fcb-layout.php';
    }

    /**
     * Code you want to run when all other plugins loaded.
     */
    public function fcb_init()
    {
        load_plugin_textdomain('fcb', false, basename(dirname(__FILE__)) . '/languages/');
    }

    /*
      |----------------------------------------------------------------------------
      | Run when activate plugin.
      |----------------------------------------------------------------------------
    */
    public static function fcb_activate() {
        update_option("fcb-v",FCB_VERSION);
        update_option("fcb-type","FREE");
        update_option("fcb-installDate",date('Y-m-d h:i:s') );
        update_option("fcb-alreadyRated","no");
    }

	// custom links for add widgets in all plugins section
	function fcb_setting_panel_action_link($link){
		$link[] = '<a style="font-weight:bold" href="'. esc_url( get_admin_url(null, 'options-general.php?page=instant_support_buttons') ) .'">Configure</a>';
		return $link;
    }
    /*
      |----------------------------------------------------------------------------
      | Run when de-activate plugin.
      |----------------------------------------------------------------------------
    */
    public static function fcb_deactivate() {
    }


}

function Floating_Contact_Buttons()
{
    return Floating_Contact_Buttons::get_instance();
}

$GLOBALS['Floating_Contact_Buttons'] = Floating_Contact_Buttons();