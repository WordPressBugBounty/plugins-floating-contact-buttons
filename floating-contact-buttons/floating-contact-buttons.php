<?php
/**
 * Plugin Name: Instant Support Buttons - Call, Contact, Chat, Email
 * Description: Floating Chat Buttons for enhancing user engagement offer various options such as callback, Skype, Slack, Instagram, WhatsApp and Telegram.
 * Author: Cool Plugins
 * Author URI: https://coolplugins.net/?utm_source=fcb_plugin&utm_medium=inside&utm_campaign=author_page&utm_content=plugins_list
 * Plugin URI: 
 * Version: 1.2.6
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

define('FCB_VERSION', '1.2.6');
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
            require_once __DIR__ . "/feedback/fcb-feedback-notice.php";
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

         if (!get_option( 'fcb-initial-save-version' ) ) {
                add_option( 'fcb-initial-save-version', FCB_VERSION );
            }
            if(!get_option( 'fcb-install-date' ) ) {
                add_option( 'fcb-install-date', gmdate('Y-m-d h:i:s') );
            }
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


    public static function fcb_get_user_info(){
         global $wpdb;
        // Server and WP environment details
        $server_info = [
            'server_software'        => isset($_SERVER['SERVER_SOFTWARE']) ? sanitize_text_field($_SERVER['SERVER_SOFTWARE']) : 'N/A',
            'mysql_version'          => $wpdb ? sanitize_text_field($wpdb->get_var("SELECT VERSION()")) : 'N/A',
            'php_version'            => sanitize_text_field(phpversion() ?: 'N/A'),
            'wp_version'             => sanitize_text_field(get_bloginfo('version') ?: 'N/A'),
            'wp_debug'               => (defined('WP_DEBUG') && WP_DEBUG) ? 'Enabled' : 'Disabled',
            'wp_memory_limit'        => sanitize_text_field(ini_get('memory_limit') ?: 'N/A'),
            'wp_max_upload_size'     => sanitize_text_field(ini_get('upload_max_filesize') ?: 'N/A'),
            'wp_permalink_structure' => sanitize_text_field(get_option('permalink_structure') ?: 'Default'),
            'wp_multisite'           => is_multisite() ? 'Enabled' : 'Disabled',
            'wp_language'            => sanitize_text_field(get_option('WPLANG') ?: get_locale()),
            'wp_prefix'              => isset($wpdb->prefix) ? sanitize_key($wpdb->prefix) : 'N/A',
        ];
        // Theme details
        $theme = wp_get_theme();
        $theme_data = [
            'name'      => sanitize_text_field($theme->get('Name')),
            'version'   => sanitize_text_field($theme->get('Version')),
            'theme_uri' => esc_url($theme->get('ThemeURI')),
        ];
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        if (!function_exists('get_plugin_data')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $plugin_data = [];
        $active_plugins = get_option('active_plugins', []);
        foreach ($active_plugins as $plugin_path) {
            $plugin_file = WP_PLUGIN_DIR . '/' . ltrim($plugin_path, '/');
            if (file_exists($plugin_file)) {
                $plugin_info = get_plugin_data($plugin_file, false, false);
                $plugin_url = !empty($plugin_info['PluginURI']) ? esc_url($plugin_info['PluginURI']) : (!empty($plugin_info['AuthorURI']) ? esc_url($plugin_info['AuthorURI']) : 'N/A');
                $plugin_data[] = [
                    'name'       => sanitize_text_field($plugin_info['Name']),
                    'version'    => sanitize_text_field($plugin_info['Version']),
                    'plugin_uri' => !empty($plugin_url) ? $plugin_url : 'N/A',
                ];
            }
        }
        return [
            'server_info'   => $server_info,
            'extra_details' => [
                'wp_theme'       => $theme_data,
                'active_plugins' => $plugin_data,
            ],
        ];
    }


}

function Floating_Contact_Buttons()
{
    return Floating_Contact_Buttons::get_instance();
}

$GLOBALS['Floating_Contact_Buttons'] = Floating_Contact_Buttons();