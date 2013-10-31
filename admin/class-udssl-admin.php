<?php
/**
 * UDSSL Theme Administration
 */
class UDSSL_Admin{
    /**
     * UDSSL Admin Menu Slug
     */
    private $admin_slug;

    /**
     * UDSSL Default Options
     */
    private $defaults;

    /**
     * UDSSL Tabs
     */
    private $tabs;

    /**
     * UDSSL Theme Constructor
     */
     function __construct(){
        /**
         * Add Admin Menu for UDSSL
         */
        add_action( 'admin_menu', array($this, 'add_udssl_admin_menu'));

        /**
         * Set UDSSL Default Options
         */
        $this->set_udssl_default_options();

        /**
         * UDSSL Options Initialization
         */
        add_action('init', array($this, 'udssl_options_init'));

        /**
         * Set UDSSL Tabs
         */
        $this->set_udssl_tabs();

        /**
         * Register UDSSL Settings
         */
  		add_action('admin_init', array($this, 'register_settings'));

     }

    /**
     * Add UDSSL Admin Menu
     */
    function add_udssl_admin_menu(){
        $this->admin_slug = add_menu_page(
            'UDSSL Administration Interface',
            'UDSSL Admin',
            'manage_options',
            'manage-udssl',
            array($this, 'udssl_admin_page'),
            UDS_URL . 'favicon.png',
            3.33
        );

        /**
         * Admin Scripts
         */
  		add_action('admin_print_scripts-' . $this->admin_slug, array($this, 'admin_scripts'));
    }

    /**
     * UDSSL Options Defaults
     */
    function set_udssl_default_options(){
        $this->defaults = array(
            'basic' => array(
                'facebook' => array(
                    'url' => 'https://facebook.com/usbservices'
                ),
                'twitter' => array(
                    'user_name' => 'udssl'
                ),
                'linkedin' => array(
                    'url' => 'http://lk.linkedin.com/in/praveenchathurangadias'
                ),
                'google' => array(
                    'url' => 'https://plus.google.com/u/0/102763545776466339141'
                ),
                'youtube' => array(
                    'url' => 'http://www.youtube.com/user/udssl'
                ),
                'stackexchange' => array(
                    'url' => 'http://stackexchange.com/users/3441747/udssl'
                ),
                'github' => array(
                    'url' => 'https://github.com/UDSSL'
                )
            ),
            'facebook' => array(
                'facebook_page_id'       => 'usbservices',
                'facebook_app_id'        => '166892090176216',
                'facebook_widget_title'  => 'Like us on Facebook',
                'facebook_widget_width'  => '235',
                'facebook_widget_height' => '240',
            ),
            'twitter' => array(
                'user_name'           => 'udssl',
                'consumer_key'        => 'D8dI1wkhwheDB9pFbw',
                'consumer_secret'     => 'q0Fpud08CfKNC7y1EnwLRXoM5VzqyGqHfDNu8NE',
                'access_token'        => '1963065091-vWKpoBCs5XStdHaNWHGy8LwE9WsBzCqWCQ',
                'access_token_secret' => '69dEulJfR9in9bdigxK4N79U4RsaRlEM0tg',
                'no_of_tweets'        => 4,
                'time_to_expire'      => 60
            ),
            'contact' => array(
                'data' => array(
                    'address' => 'Kumbuka, Gonapola, Sri Lanka',
                    'phone' => '(+94) 077 177 1180',
                    'email' => get_option('admin_email'),
                    'skype' => 'praveen.udssl'
                ),
                'form' => array(
                    'email' => get_option('admin_email')
                ),
                'map' => array(
                    'latitude' => '6.924723',
                    'longitude' => '79.849952',
                    'center_x' => '6.924723',
                    'center_y' => '79.849952',
                    'zoom' => '10'
                )
            ),
            'newsletter' => array(
                'apikey' => '19f92aec44sdfsobf782-us5',
                'id' => '28fa4sd54',
                'url' => 'https://api.mailchimp.com/2.0/lists/subscribe.json',
                'default_name' => 'UDSSLWebSite'
            ),
            'recaptcha' => array(
                'private_key' => 'aaabbbccc123456',
                'public_key' => 'bbbccc123456',
                'enabled' => true
            ),
            'users' => array(
                'login_expiration_time' => 60,
                'take_over' => false
            ),
            'cron' => array(
                'report' => array(
                    'interval' => 60
                )
            )
        );
    }

    /**
     * UDSSL Options Initialization
     */
    function udssl_options_init(){
        $udssl_options = get_option('udssl_options');
        if( false == $udssl_options )
            update_option('udssl_options', $this->defaults);
    }

    /**
     * UDSSL Tabs
     */
    function set_udssl_tabs(){
        $this->tabs = array(
            'basic'      => __('Basic', 'udssl'),
            'facebook'   => __('Facebook', 'udssl'),
            'twitter'    => __('Twitter', 'udssl'),
            'contact'    => __('Contact', 'udssl'),
            'newsletter' => __('Newsletter', 'udssl'),
            'recaptcha'  => __('reCaptcha', 'udssl'),
            'users'      => __('Users', 'udssl'),
            'cron'       => __('Cron', 'udssl')
        );
    }

    /**
     * UDSSL Admin Page
     */
    function udssl_admin_page(){
  		echo '<div class="wrap">';
            echo '<div id="icon-udssl" class="icon32"><br /></div>';
            echo '<h2>' . __('UDSSL Dashboard', 'udssl') . '</h2>';
            $this->udssl_settings_tabs();
            settings_errors();
            echo '<form action="options.php" method="post">';
                if ( isset ( $_GET['tab'] ) ) :
                    $tab = $_GET['tab'];
                else:
                    $tab = 'basic';
                endif;

                switch ( $tab ) :
                case 'basic' :
                    require UDS_PATH . 'admin/tabs/tab-basic.php';
                    break;
                case 'facebook' :
                    require UDS_PATH . 'admin/tabs/tab-facebook.php';
                    break;
                case 'twitter' :
                    require UDS_PATH . 'admin/tabs/tab-twitter.php';
                    break;
                case 'contact' :
                    require UDS_PATH . 'admin/tabs/tab-contact.php';
                    break;
                case 'newsletter' :
                    require UDS_PATH . 'admin/tabs/tab-newsletter.php';
                    break;
                case 'recaptcha' :
                    require UDS_PATH . 'admin/tabs/tab-recaptcha.php';
                    break;
                case 'users' :
                    require UDS_PATH . 'admin/tabs/tab-users.php';
                    break;
                case 'cron' :
                    require UDS_PATH . 'admin/tabs/tab-cron.php';
                    break;
                endswitch;

                settings_fields('udssl_options');
                do_settings_sections('manage-udssl');

            echo '</form>';
  		echo '</div>';
    }

    /**
     * UDSSL Tabs
     */
    function udssl_settings_tabs(){
        if ( isset ( $_GET['tab'] ) ) :
            $current = $_GET['tab'];
        else:
            $current = 'basic';
        endif;

        $links = array();
        foreach( $this->tabs as $tab => $name ) :
            if ( $tab == $current ) :
                $links[] = '<a class="nav-tab nav-tab-active"
                href="?page=manage-udssl&tab=' .
                $tab . '" > ' . $name . '</a>';
            else :
                $links[] = '<a class="nav-tab"
                href="?page=manage-udssl&tab=' .
                $tab . '" >' . $name . '</a>';
            endif;
        endforeach;

        echo '<h2 class="nav-tab-wrapper">';
            foreach ( $links as $link ):
                echo $link;
            endforeach;
        echo '</h2>';
    }

    /**
     * Register UDSSL Settings
     */
	function register_settings(){
        register_setting(
            'udssl_options',
            'udssl_options',
            array( $this, 'udssl_options_validate' )
        );
	}

    /**
     * UDSSL Options Validate
     */
    function udssl_options_validate($input){
        $options = get_option('udssl_options');
        $output = $options;

        /**
         * Save Basic Settings
         */
  		if(isset($input['save_basic'])){
            $output['basic']['facebook']['url'] = sanitize_text_field($input['basic']['facebook']['url']);
            $output['basic']['twitter']['user_name'] = sanitize_text_field($input['basic']['twitter']['user_name']);
            $output['basic']['linkedin']['url'] = sanitize_text_field($input['basic']['linkedin']['url']);
            $output['basic']['google']['url'] = sanitize_text_field($input['basic']['google']['url']);
            $output['basic']['stackexchange']['url'] = sanitize_text_field($input['basic']['stackexchange']['url']);
            $output['basic']['youtube']['url'] = sanitize_text_field($input['basic']['youtube']['url']);
            $output['basic']['github']['url'] = sanitize_text_field($input['basic']['github']['url']);

            $message = 'UDSSL Basic Settings Saved';
            $type = 'updated';
  		}

        /**
         * Reset Basic Settings
         */
  		if(isset($input['reset_basic'])){
            $output['basic'] = $this->defaults['basic'];

            $message = 'UDSSL Basic Settings Reset';
            $type = 'updated';
  		}

         /**
          * Facebook Save Settings - Reset
          */
         if(isset($input['submit-facebook'])){
             $output['facebook']['facebook_app_id'] = $input['facebook_app_id'];
             $output['facebook']['facebook_page_id'] = $input['facebook_page_id'];
             $output['facebook']['facebook_widget_title'] = $input['facebook_widget_title'];
             $output['facebook']['facebook_widget_width'] = $input['facebook_widget_width'];
             $output['facebook']['facebook_widget_height'] = $input['facebook_widget_height'];

            $message = 'UDSSL Facebook Settings Saved';
            $type = 'updated';
         } elseif (isset($input['reset-facebook'])) {
             $output['facebook'] = $this->defaults['facebook'];

            $message = 'UDSSL Facebook Settings Reset';
            $type = 'updated';
         }

         /**
          * Twitter Save Settings - Reset - Reauthenticate
          */
         if(isset($input['submit-twitter'])){
             $output['twitter']['user_name'] = $input['twitter']['user_name'];
             $output['twitter']['consumer_key'] = $input['twitter']['consumer_key'];
             $output['twitter']['consumer_secret'] = $input['twitter']['consumer_secret'];
             $output['twitter']['access_token'] = $input['twitter']['access_token'];
             $output['twitter']['access_token_secret'] = $input['twitter']['access_token_secret'];
             $output['twitter']['no_of_tweets'] = $input['twitter']['no_of_tweets'];
             $output['twitter']['time_to_expire'] = $input['twitter']['time_to_expire'];
             delete_transient('twitter_user_timeline');

            $message = 'UDSSL Twitter Settings Saved';
            $type = 'updated';
         } elseif (isset($input['reset-twitter'])) {
            $output['twitter'] = $this->defaults['twitter'];
             delete_transient('twitter_user_timeline');

            $message = 'UDSSL Twitter Settings Reset';
            $type = 'updated';
         } elseif (isset($input['delete-twitter'])) {
             delete_transient('twitter_user_timeline');

            $message = 'UDSSL Twitter Timeline Deleted';
            $type = 'updated';
         } elseif (isset($input['reauthenticate-twitter'])) {
             echo 'redirecting';
             exit;
         }

        /**
         * Save Contact Settings
         */
  		if(isset($input['save_contact'])){
            $output['contact']['data']['address'] = sanitize_text_field($input['contact']['data']['address']);
            $output['contact']['data']['phone'] = sanitize_text_field($input['contact']['data']['phone']);
            $output['contact']['data']['email'] = sanitize_email($input['contact']['data']['email']);

            $output['contact']['form']['email'] = sanitize_email($input['contact']['form']['email']);

            $output['contact']['map']['latitude'] = sanitize_text_field($input['contact']['map']['latitude']);
            $output['contact']['map']['longitude'] = sanitize_text_field($input['contact']['map']['longitude']);
            $output['contact']['map']['center_x'] = sanitize_text_field($input['contact']['map']['center_x']);
            $output['contact']['map']['center_y'] = sanitize_text_field($input['contact']['map']['center_y']);
            $output['contact']['map']['zoom'] = sanitize_text_field($input['contact']['map']['zoom']);

            $message = 'UDSSL Contact Settings Saved';
            $type = 'updated';
  		}

        /**
         * Reset Contact Settings
         */
  		if(isset($input['reset_contact'])){
            $output['contact'] = $this->defaults['contact'];

            $message = 'UDSSL Contact Settings Reset';
            $type = 'updated';
  		}

        /**
         * Save Newsletter Settings
         */
  		if(isset($input['save_newsletter'])){
            $output['newsletter']['apikey'] = sanitize_text_field($input['newsletter']['apikey']);
            $output['newsletter']['id'] = sanitize_text_field($input['newsletter']['id']);
            $output['newsletter']['url'] = esc_url($input['newsletter']['url']);
            $output['newsletter']['default_name'] = sanitize_text_field($input['newsletter']['default_name']);

            $message = 'UDSSL Newsletter Settings Saved';
            $type = 'updated';
  		}

        /**
         * Reset Newsletter Settings
         */
  		if(isset($input['reset_newsletter'])){
            $output['newsletter'] = $this->defaults['newsletter'];

            $message = 'UDSSL Newsletter Settings Reset';
            $type = 'updated';
  		}

        /**
        * reCaptcha Save Settings - Reset
        */
        if(isset($input['submit-recaptcha'])){
            $output['recaptcha']['private_key'] = sanitize_text_field($input['recaptcha']['private_key']);
            $output['recaptcha']['public_key'] = sanitize_text_field($input['recaptcha']['public_key']);
            $output['recaptcha']['enabled'] = isset($input['recaptcha']['enabled']) ? true : false;

            $message = 'UDSSL reCaptcha Settings Saved';
            $type = 'updated';
        } elseif (isset($input['reset-recaptcha'])) {
            $output['recaptcha'] = $this->defaults['recaptcha'];

            $message = 'UDSSL reCaptcha Settings Reset';
            $type = 'updated';
        }

         /**
          * Users Save Settings - Reset
          */
         if(isset($input['submit-users'])){
             $output['users']['login_expiration_time'] = $input['users']['login_expiration_time'];
             $output['users']['take_over'] = isset($input['users']['take_over']) ? true : false;
         } elseif (isset($input['reset-users'])) {
             $output['users'] = $this->defaults['users'];
         }

         /**
          * Logout Users
          */
        $logged_in = get_option('udssl_logged_in_users');
        if(false == $logged_in){
            $logged_in = array();
        }
        foreach($logged_in as $user_id => $user){
             if(isset($input['logout_' . $user_id])){
                if(isset($logged_in[$user_id])){
                    unset($logged_in[$user_id]);
                }
                update_option('udssl_logged_in_users', $logged_in);
             }
        }

        /**
         * UDSSL Cron Reschedule
         */
        if($output['cron']['report']['interval'] !=
            $input['report']['interval'] ):

            global $udssl_theme;
            $udssl_theme->cron->schedule_event(
                'udssl_report',
                $input['report']['interval']);
            $message = 'UDSSL Cron Rescheduled';
            $type = 'updated';
        endif;

        /**
        * Cron Save Settings - Reset
        */
        if(isset($input['submit-cron'])){
            $output['cron']['report']['interval'] = $input['report']['interval'];

            $message = 'UDSSL Cron Configuration Saved';
            $type = 'updated';
        } elseif (isset($input['reset-cron'])) {
            $output['cron'] = $this->defaults['cron'];

            $message = 'UDSSL Cron Configuration Reset';
            $type = 'updated';
        }

        add_settings_error(
            'udssl',
            esc_attr('settings_updated'),
            __($message),
            $type
        );

        return $output;
    }

    /**
     * Admin Scripts
     */
    function admin_scripts(){
        wp_enqueue_style( 'udssl-admin', UDS_URL . 'css/udssl-admin.css' );
    }
}
?>
