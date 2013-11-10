<?php
/**
 * UDSSL Store Administration
 */
class UDSSL_Store_Admin{
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
         * Add Admin Menu for UDSSL Store
         */
        add_action( 'admin_menu', array($this, 'add_store_admin_menu'));

        /**
         * Set UDSSL Store Default Options
         */
        $this->set_store_default_options();

        /**
         * UDSSL Store Options Initialization
         */
        add_action('init', array($this, 'store_options_init'));

        /**
         * Set UDSSL Store Tabs
         */
        $this->set_store_tabs();

        /**
         * Register UDSSL Store Settings
         */
  		add_action('admin_init', array($this, 'register_settings'));

     }

    /**
     * Add UDSSL Store Admin Menu
     */
    function add_store_admin_menu(){
        $this->admin_slug = add_menu_page(
            __('UDSSL Store Administration', 'udssl'),
            __('UDSSL Store', 'udssl'),
            'manage_options',
            'manage-udssl-store',
            array($this, 'store_admin_page'),
            UDS_URL . 'favicon.png'
        );

        /**
         * Admin Scripts
         */
  		add_action('admin_print_scripts-' . $this->admin_slug, array($this, 'admin_scripts'));
    }

    /**
     * UDSSL Store Options Defaults
     */
    function set_store_default_options(){
        $this->defaults = array(
            'paypal' => array(
                'paypal_classic' => array(
                    'username' => 'sampleusername',
                    'password' => 'sampleupassword',
                    'signature' => 'samplesignature',
                    'sandbox_mode' => true
                )
            )
        );
    }

    /**
     * UDSSL Store Options Initialization
     */
    function store_options_init(){
        $udssl_store_options = get_option('udssl_store_options');
        if( false == $udssl_store_options )
            update_option('udssl_store_options', $this->defaults);
    }

    /**
     * UDSSL Store Tabs
     */
    function set_store_tabs(){
        $this->tabs = array(
            'sales' => __('Sales', 'udssl'),
            'paypal' => __('PayPal', 'udssl')
        );
    }

    /**
     * UDSSL Store Admin Page
     */
    function store_admin_page(){
  		echo '<div class="wrap">';
            echo '<div id="icon-udssl-store" class="icon32"><br /></div>';
            echo '<h2>' . __('UDSSL Store Dashboard', 'udssl') . '</h2>';
            $this->store_settings_tabs();
            settings_errors();
            echo '<form action="options.php" method="post">';
                if ( isset ( $_GET['tab'] ) ) :
                    $tab = $_GET['tab'];
                else:
                    $tab = 'sales';
                endif;

                switch ( $tab ) :
                case 'sales' :
                    require UDS_PATH . 'store/admin/tabs/tab-sales.php';
                    break;
                case 'paypal' :
                    require UDS_PATH . 'store/admin/tabs/tab-paypal.php';
                    break;
                endswitch;

                settings_fields('udssl_store_options');
                do_settings_sections('manage-udssl-store');

            echo '</form>';
  		echo '</div>';
    }

    /**
     * UDSSL Tabs
     */
    function store_settings_tabs(){
        if ( isset ( $_GET['tab'] ) ) :
            $current = $_GET['tab'];
        else:
            $current = 'sales';
        endif;

        $links = array();
        foreach( $this->tabs as $tab => $name ) :
            if ( $tab == $current ) :
                $links[] = '<a class="nav-tab nav-tab-active"
                href="?page=manage-udssl-store&tab=' .
                $tab . '" > ' . $name . '</a>';
            else :
                $links[] = '<a class="nav-tab"
                href="?page=manage-udssl-store&tab=' .
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
            'udssl_store_options',
            'udssl_store_options',
            array( $this, 'store_options_validate' )
        );
	}

    /**
     * UDSSL Store Options Validate
     */
    function store_options_validate($input){
        $options = get_option('udssl_store_options');
        $output = $options;

        /**
         * Save PayPal Settings
         */
  		if(isset($input['save_paypal'])){
            $output['paypal']['paypal_classic']['username'] = sanitize_text_field($input['paypal']['paypal_classic']['username']);
            $output['paypal']['paypal_classic']['password'] = sanitize_text_field($input['paypal']['paypal_classic']['password']);
            $output['paypal']['paypal_classic']['signature'] = sanitize_text_field($input['paypal']['paypal_classic']['signature']);
            $output['paypal']['paypal_classic']['sandbox_mode'] = isset($input['paypal']['paypal_classic']['sandbox_mode']) ? true : false;

            $message = 'UDSSL PayPal Settings Saved';
            $type = 'updated';
  		}

        /**
         * Reset PayPal Settings
         */
  		if(isset($input['reset_paypal'])){
            $output['paypal'] = $this->defaults['paypal'];

            $message = 'UDSSL PayPal Settings Reset';
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
