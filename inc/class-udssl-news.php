<?php
/**
 * UDSSL News
 */
class UDSSL_News{
    /**
     * Constructor
     */
    function __construct(){
        add_action('init', array($this, 'register_news_post_type'));
    }

    /**
     * Register News Post Type
     */
    function register_news_post_type(){
        $labels = array(
            'name'          => __('News', 'udssl'),
            'singular_name' => __('News', 'udssl'),
            'add_new'       => __('Add News', 'udssl'),
            'view_item'     => __('View News Page', 'udssl'),
            'add_new_item'  => __('Add New News', 'udssl'),
            'edit_item'     => __('Edit News', 'udssl')
        );
        $args = array(
            'labels'        => $labels,
            'public'        => true,
            'supports'      => array('title', 'editor', 'thumbnail'),
            'menu_icon'     => UDS_URL . 'favicon.png'
        );
        register_post_type( 'news', $args );
    }
}
?>
