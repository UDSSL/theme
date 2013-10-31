<?php
/**
 * UDSSL Analytics
 */
class UDSSL_Analytics{
    /**
     * UDSSL Analytics Menu Slug
     */
    private $admin_slug;

    /**
     * UDSSL Default Options
     */
    private $defaults;

    /**
     * UDSSL Analytics Tabs
     */
    private $tabs;

    /**
     * UDSSL Theme Constructor
     */
     function __construct(){
        /**
         * Add Analytics Menu for UDSSL
         */
        add_action( 'admin_menu', array($this, 'add_udssl_admin_menu'));

        /**
         * Set UDSSL Default Options
         */
        $this->set_udssl_default_options();

        /**
         * UDSSL Options Initialization
         */
        add_action('init', array($this, 'udssl_analytics_options_init'));

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
     * Add UDSSL Analytics Menu
     */
    function add_udssl_admin_menu(){
        $this->admin_slug = add_menu_page(
            'UDSSL Analytics Interface',
            'UDSSL Analytics',
            'manage_options',
            'udssl-analytics',
            array($this, 'udssl_analytics_page'),
            UDS_URL . 'favicon.png',
            4.33
        );

        /**
         * Analytics Scripts
         */
  		add_action('admin_print_scripts-' . $this->admin_slug, array($this, 'admin_scripts'));
    }

    /**
     * UDSSL Options Defaults
     */
    function set_udssl_default_options(){
        $this->defaults = array(
            'facebook' => array(
                'facebook_page_id'       => 'usbservices',
                'facebook_app_id'        => '166892090176216',
                'facebook_widget_title'  => 'Like us on Facebook',
                'facebook_widget_width'  => '235',
                'facebook_widget_height' => '240',
            )
        );
    }

    /**
     * UDSSL Options Initialization
     */
    function udssl_analytics_options_init(){
        //delete_option('udssl_analytics_options');
        $udssl_analytics_options = get_option('udssl_analytics_options');
        if( false == $udssl_analytics_options )
            update_option('udssl_analytics_options', $this->defaults);
    }

    /**
     * UDSSL Tabs
     */
    function set_udssl_tabs(){
        $this->tabs = array(
            'visits'  => __('Visits', 'udssl'),
            'users'   => __('Users', 'udssl'),
            'spam'    => __('Spam', 'udssl'),
        );
    }

    /**
     * UDSSL Analytics Page
     */
    function udssl_analytics_page(){
  		echo '<div class="wrap">';
            echo '<div id="icon-udssl" class="icon32"><br /></div>';
            echo '<h2>' . __('UDSSL Analytics', 'udssl') . '</h2>';
            $this->udssl_settings_tabs();
            settings_errors();
            echo '<form action="options.php" method="post">';
                if ( isset ( $_GET['tab'] ) ) :
                    $tab = $_GET['tab'];
                else:
                    $tab = 'visits';
                endif;

                switch ( $tab ) :
                case 'visits' :
                    require UDS_PATH . 'admin/analyze/tab-visits.php';
                    break;
                case 'users' :
                    require UDS_PATH . 'admin/analyze/tab-users.php';
                    break;
                case 'spam' :
                    require UDS_PATH . 'admin/analyze/tab-spam.php';
                    break;
                endswitch;

                settings_fields('udssl_analytics_options');
                do_settings_sections('udssl-analytics');

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
            $current = 'visits';
        endif;

        $links = array();
        foreach( $this->tabs as $tab => $name ) :
            if ( $tab == $current ) :
                $links[] = '<a class="nav-tab nav-tab-active"
                href="?page=udssl-analytics&tab=' .
                $tab . '" > ' . $name . '</a>';
            else :
                $links[] = '<a class="nav-tab"
                href="?page=udssl-analytics&tab=' .
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
            'udssl_analytics_options',
            'udssl_analytics_options',
            array( $this, 'udssl_analytics_options_validate' )
        );
	}

    /**
     * UDSSL Analytics Options Validate
     */
    function udssl_analytics_options_validate($input){
        $options = get_option('udssl_analytics_options');
        $output = $options;

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

        add_settings_error(
            'udssl',
            esc_attr('settings_updated'),
            __($message),
            $type
        );

        return $output;
    }

    /**
     * Analytics Scripts
     */
    function admin_scripts(){
        wp_enqueue_style( 'udssl-admin', UDS_URL . 'css/udssl-admin.css' );
    }
}
?>
