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
        add_action( 'init', array($this, 'project_tax'));
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

    /**
     * Projects Taxonomy
     */
    function project_tax() {
        $labels = array(
            'name'              => _x( 'Projects', 'taxonomy general name' ),
            'singular_name'     => _x( 'Project', 'taxonomy singular name' ),
            'search_items'      => __( 'Search Projects' ),
            'all_items'         => __( 'All Projects' ),
            'parent_item'       => __( 'Parent Project' ),
            'parent_item_colon' => __( 'Parent Project:' ),
            'edit_item'         => __( 'Edit Project' ),
            'update_item'       => __( 'Update Project' ),
            'add_new_item'      => __( 'Add New Project' ),
            'new_item_name'     => __( 'New Project Name' ),
            'menu_name'         => __( 'Project' ),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'udssl-project' ),
        );

        register_taxonomy( 'udssl-project', array('projects', 'page'), $args );
    }
}
?>
