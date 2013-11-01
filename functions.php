<?php
/**
 * UDSSL Theme Bootstrapper
 */
if ( !defined('UDS_PATH') )
    define( 'UDS_PATH', get_stylesheet_directory() . '/' );
if ( !defined('UDS_URL') )
    define( 'UDS_URL', get_stylesheet_directory_uri() . '/' );

class UDSSL_Theme{
    /**
     * Projects Post Type
     */
    private $projects;

    /**
     * WordPress Plugin Pages
     */
    private $plugin_pages;

    /**
     * WordPress Theme Pages
     */
    private $theme_pages;

    /**
     * News
     */
    private $news;

    /**
     * SEO
     */
    private $seo;

    /**
     * Admin
     */
    private $admin;

    /**
     * Analytics
     */
    private $analytics;

    /**
     * Enqueues
     */
    private $enqueues;

    /**
     * WP Theme
     */
    public $wptheme;

    /**
     * Sidebars
     */
    public $sidebar;

    /**
     * Header
     */
    public $header;

    /**
     * Contact
     */
    public $contact;

    /**
     * Facebook
     */
    public $facebook;

    /**
     * Twitter
     */
    public $twitter;

    /**
     * Sitemap
     */
    public $sitemap;

    /**
     * Editor
     */
    private $editor;

    /**
     * Leave
     */
    public $leave;

    /**
     * Security
     */
    public $security;

    /**
     * Membership
     */
    public $membership;

    /**
     * Mailchimp Newsletter
     */
    public $mailchimp;

    /**
     * Search
     */
    public $search;

    /**
     * Session
     */
    public $session;

    /**
     * Utilities
     */
    public $utils;

    /**
     * Cron
     */
    public $cron;

    /**
     * Downloads
     */
    public $downloads;

    /**
     * Router
     */
    public $router;

    /**
     * Constructor
     */
    function __construct(){
        $this->init();

        require UDS_PATH . 'inc/class-udssl-projects.php';
        $this->projects = new UDSSL_Projects();

        require UDS_PATH . 'inc/class-udssl-plugin.php';
        $this->plugin_pages = new UDSSL_Plugin_Pages();

        require UDS_PATH . 'inc/class-udssl-theme.php';
        $this->theme_pages = new UDSSL_Theme_Pages();

        require UDS_PATH . 'inc/class-udssl-news.php';
        $this->news = new UDSSL_News();

        require UDS_PATH . 'inc/class-udssl-seo.php';
        $this->seo = new UDSSL_SEO();

        require UDS_PATH . 'admin/class-udssl-admin.php';
        $this->admin = new UDSSL_Admin();

        require UDS_PATH . 'inc/class-udssl-enqueues.php';
        $this->enqueues = new UDSSL_Enqueues();

        require UDS_PATH . 'inc/class-udssl-wptheme.php';
        $this->wptheme = new UDSSL_WPTheme();

        require UDS_PATH . 'inc/class-udssl-sidebar.php';
        $this->sidebar = new UDSSL_Sidebar();

        require UDS_PATH . 'inc/class-udssl-header.php';
        $this->header = new UDSSL_Header();

        require UDS_PATH . 'inc/class-udssl-contact.php';
        $this->contact = new UDSSL_Contact();

        require UDS_PATH . 'inc/class-udssl-facebook.php';
        $this->facebook = new UDSSL_Facebook();

        require UDS_PATH . 'widgets/widget-twitter.php';
        $this->twitter = new WP_Twitter();

        require UDS_PATH . 'inc/class-udssl-sitemap.php';
        $this->sitemap = new UDSSL_Sitemap();

        require UDS_PATH . 'inc/class-udssl-editor.php';
        $this->editor = new UDSSL_Editor();

        require UDS_PATH . 'inc/class-udssl-leave.php';
        $this->leave = new UDSSL_Leave();

        require UDS_PATH . 'inc/class-udssl-security.php';
        $this->security = new UDSSL_Security();

        require UDS_PATH . 'inc/class-udssl-membership.php';
        $this->membership = new UDSSL_Membership();

        require UDS_PATH . 'inc/class-udssl-mailchimp.php';
        $this->mailchimp = new UDSSL_Mailchimp();

        require UDS_PATH . 'inc/class-udssl-search.php';
        $this->search = new UDSSL_Search();

        require UDS_PATH . 'inc/class-udssl-session.php';
        $this->session = new UDSSL_Session();

        require UDS_PATH . 'inc/class-udssl-utilities.php';
        $this->utils = new UDSSL_Utilities();

        require UDS_PATH . 'cron/class-udssl-cron.php';
        $this->cron = new UDSSL_Cron();

        require UDS_PATH . 'admin/class-udssl-analytics.php';
        $this->analytics = new UDSSL_Analytics();

        require UDS_PATH . 'inc/class-udssl-downloads.php';
        $this->downloads = new UDSSL_Downloads();

        require UDS_PATH . 'inc/class-udssl-router.php';
        $this->router = new UDSSL_Router();
    }

    /**
     * Initialize UDSSL Theme
     */
    function init(){
        add_action('after_setup_theme', array($this, 'after_setup_theme'));
    }

    /**
     * After UDSSL Theme Setup
     */
    function after_setup_theme(){
        add_theme_support('post-thumbnails');
    }

    /**
     * UDSSL Theme Installation Routine
     */
    function install(){
        $this->security->create_visitor_table();
        $this->cron->activation();

        /**
         * Call All Rewrites
         */
        $this->downloads->download_rewrite();
        $this->router->router_rewrite();

        flush_rewrite_rules();
    }

    /**
     * UDSSL Theme Uninstallation Routine
     */
    function uninstall(){
        $this->cron->dectivation();
        flush_rewrite_rules();
    }
}

/**
 * Instantiate UDSSL Theme
 */
$udssl_theme = new UDSSL_Theme();
?>
