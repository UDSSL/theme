<?php
/**
 * UDSSL Projects
 */
class UDSSL_Projects{
    /**
     * Constructor
     */
    function __construct(){
        add_action('init', array($this, 'register_projects_post_type'));
    }

    /**
     * Register Projects Post Type
     */
    function register_projects_post_type(){
        $labels = array(
            'name'          => __('Projects', 'udssl'),
            'singular_name' => __('Project', 'udssl'),
            'add_new'       => __('Add Project', 'udssl'),
            'view_item'     => __('View Project Page', 'udssl'),
            'add_new_item'  => __('Add New Project', 'udssl'),
            'edit_item'     => __('Edit Project', 'udssl')
        );
        $args = array(
            'labels'        => $labels,
            'public'        => true,
            'supports'      => array('title', 'editor', 'thumbnail'),
            'menu_icon'     => UDS_URL . 'favicon.png'
        );
        register_post_type( 'projects', $args );
    }
}
?>
