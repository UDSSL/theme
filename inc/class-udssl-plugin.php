<?php
/**
 * UDSSL Plugin Pages
 */
class UDSSL_Plugin_Pages{
    /**
     * Constructor
     */
    function __construct(){
        add_action('init', array($this, 'register_plugin_pages_post_type'));
    }

    /**
     * Register Plugin Pages Post Type
     */
    function register_plugin_pages_post_type(){
        $labels = array(
            'name'          => __('Plugin Pages', 'udssl'),
            'singular_name' => __('Plugin Page', 'udssl'),
            'add_new'       => __('Add Plugin Page', 'udssl'),
            'view_item'     => __('View Plugin Page', 'udssl'),
            'add_new_item'  => __('Add New Plugin Page', 'udssl'),
            'edit_item'     => __('Edit Plugin Page', 'udssl')
        );
        $args = array(
            'labels'        => $labels,
            'public'        => true,
            'supports'      => array('title', 'editor', 'thumbnail'),
            'menu_icon'     => UDS_URL . 'favicon.png'
        );
        register_post_type( 'udssl-time-tracker', $args );
    }
}
?>
