<?php
/**
 * UDSSL Theme Pages
 */
class UDSSL_Theme_Pages{
    /**
     * Constructor
     */
    function __construct(){
        add_action('init', array($this, 'register_theme_pages_post_type'));
    }

    /**
     * Register Theme Pages Post Type
     */
    function register_theme_pages_post_type(){
        $labels = array(
            'name'          => __('Theme Pages', 'udssl'),
            'singular_name' => __('Theme Page', 'udssl'),
            'add_new'       => __('Add Theme Page', 'udssl'),
            'view_item'     => __('View Theme Page Page', 'udssl'),
            'add_new_item'  => __('Add New Theme Page', 'udssl'),
            'edit_item'     => __('Edit Theme Page', 'udssl')
        );
        $args = array(
            'labels'        => $labels,
            'public'        => true,
            'supports'      => array('title', 'editor', 'thumbnail'),
            'menu_icon'     => UDS_URL . 'favicon.png'
        );
        register_post_type( 'udssl-theme', $args );
    }
}
?>
