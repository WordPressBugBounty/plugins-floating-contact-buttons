<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('fcbFeedbackNotice')) {
    class fcbFeedbackNotice {
        /**
         * The Constructor
         */
        public function __construct() {
            // register actions
         
            if(is_admin()){
                add_action( 'admin_notices',array($this,'fcb_admin_notice_for_reviews'));
                add_action( 'admin_enqueue_scripts', array($this, 'fcb_load_script' ) );
                add_action( 'wp_ajax_fcb_dismiss_notice',array($this,'fcb_dismiss_review_notice' ) );
            }
        }

        /**
         * Load script to dismiss notices.
         *
         * @return void
         */
        public function fcb_load_script() {
            wp_register_script( 'fcb-feedback-notice-script', plugin_dir_url(__FILE__) . '/js/fcb-admin-feedback-notice.js', array( 'jquery' ), FCB_VERSION, true );
           
            wp_register_style( 'fcb-feedback-notice-styles', plugin_dir_url(__FILE__) . '/css/fcb-admin-feedback-notice.css', array(), FCB_VERSION );
        }
        
        // ajax callback for review notice
        public function fcb_dismiss_review_notice() {
            if ( ! isset( $_POST['private'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['private'] ) ), 'fcb_review_notice_private' ) ) {
                return wp_send_json_error( array( 'message' => 'nonce verification failed' ) );
                
            }

            if ( ! current_user_can( 'manage_options' ) ) {
                return wp_send_json_error( array( 'message' => 'Unauthorized' ), 403 );
            }
        
            update_option( 'fcb-alreadyRated', 'yes' );
            echo json_encode( array( "success" => "true" ) );
            exit;
        }
        // admin notice  
        public function fcb_admin_notice_for_reviews(){
            if( !current_user_can( 'manage_options' ) ){
                return;
            }
            // get installation dates and rated settings
            $installation_date = get_option( 'fcb-installDate' );
            $alreadyRated =get_option( 'fcb-alreadyRated' )!=false?get_option( 'fcb-alreadyRated'):"no";
            
            if(null != get_option('fcb-ratingDiv')){
                $ratingDiv = get_option('fcb-ratingDiv');
                if($ratingDiv=="yes"){
                    $alreadyRated ="yes";
                }
            }

            // check user already rated 
            if( $alreadyRated=="yes") {
                    return;
                }
                
                // grab plugin installation date and compare it with current date
                $display_date = gmdate( 'Y-m-d h:i:s' );
                $install_date= new DateTime( $installation_date );
                $current_date = new DateTime( $display_date );
                $difference = $install_date->diff($current_date);
                $diff_days= $difference->days;
                
                // check if installation days is greator then week
                if (isset($diff_days) && $diff_days>=3) {
                    wp_enqueue_script( 'fcb-feedback-notice-script' );
                    wp_enqueue_style( 'fcb-feedback-notice-styles' );
                    echo $this->create_notice_content(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }
        }  

        // generated review notice HTML
        function create_notice_content(){
            
        $ajax_url      = admin_url( 'admin-ajax.php' );
        $ajax_callback = 'fcb_dismiss_notice';
        $wrap_cls      = 'notice notice-info is-dismissible';
        $p_name        = 'Instant Support Buttons';
        $like_it_text  = 'Rate Now! ★★★★★';

        $already_rated_text = esc_html__( 'Already Reviewed', 'floating-contact-buttons' );
        $not_like_it_text   = esc_html__( 'No, not good enough, i do not like to rate it!', 'floating-contact-buttons' );
        $not_interested     = esc_html__( 'Not Interested', 'floating-contact-buttons' );

        $p_link = esc_url(
            'https://wordpress.org/support/plugin/floating-contact-buttons/reviews/#new-post'
        );

        $nonce = wp_create_nonce( 'fcb_review_notice_private' );

        $message = sprintf(
            'Thanks for using <b>%1$s</b> - WordPress plugin. We hope you liked it ! <br/>Please give us a quick rating, it works as a boost for us to keep working on more <a href="%2$s" target="_blank"><strong>Cool Plugins</strong></a>!<br/>',
            esc_html( $p_name ),
            esc_url( 'https://coolplugins.net' )
        );

        $html = '
        <div data-nonce="%9$s" data-ajax-url="%6$s" data-ajax-callback="%7$s" class="cool-feedback-notice-wrapper %1$s">
            <div class="message_container">
                %2$s
                <div class="callto_action">
                    <ul>
                        <li class="love_it">
                            <a href="%3$s" class="like_it_btn button button-primary" target="_new" title="%4$s">%4$s</a>
                        </li>
                        <li class="already_rated">
                            <a href="javascript:void(0);" class="already_rated_btn button fcb_dismiss_notice" title="%5$s">%5$s</a>
                        </li>
                        <li class="already_rated">
                            <a href="javascript:void(0);" class="already_rated_btn button fcb_dismiss_notice" title="%8$s">%8$s</a>
                        </li>
                    </ul>
                    <div class="clrfix"></div>
                </div>
            </div>
        </div>';

        return sprintf(
            $html,
            esc_attr( $wrap_cls ),       // %1$s
            $message,                    // %2$s
            esc_url( $p_link ),          // %3$s
            esc_html( $like_it_text ),   // %4$s
            $already_rated_text,         // %5$s
            esc_url( $ajax_url ),        // %6$s
            esc_attr( $ajax_callback ),  // %7$s
            $not_interested,             // %8$s
            esc_attr( $nonce )           // %9$s
        );
            
        }

    } //class end

} 



