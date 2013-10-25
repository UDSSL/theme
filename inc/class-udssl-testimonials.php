<?php
/**
 * UDSSL Testimonials
 */
class UDSSL_Testimonials{
    /**
     * Constructor
     */
    function __construct(){
        add_action( 'init', array($this, 'register_testimonials'));
    }

    /**
     * Register Testimonials
     */
    function register_testimonials(){
        $labels = array(
            'name' => __('Testimonials', 'udssl'),
            'singular_name' => __('Testimonial', 'udssl'),
            'add_new' => __('Add New', 'udssl'),
            'add_new_item' => __('Add New Testimonial', 'udssl'),
            'edit_item' => __('Edit Testimonial', 'udssl'),
            'new_item' => __('New Testimonial', 'udssl'),
            'all_items' => __('All Testimonials', 'udssl'),
            'view_item' => __('View Testimonial', 'udssl'),
            'search_items' => __('Search Testimonials', 'udssl'),
            'not_found' =>  __('No testimonials found', 'udssl'),
            'not_found_in_trash' => __('No testimonials found in Trash', 'udssl'),
            'parent_item_colon' => '',
            'menu_name' => __('Testimonials', 'udssl')
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'testimonial' ),
            'capability_type' => 'post',
            'has_archive' => true,
            'menu_icon' => UDS_URL . 'img/favicon.ico',
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array( 'title', 'editor', 'thumbnail')
        );

        register_post_type( 'testimonial', $args );
    }

    /**
     * Get Testimonials
     */
    function get_testimonials($limit){
        $posts = get_posts('posts_per_page=2&post_type=testimonial');
        $testimonials = array();
        foreach($posts as $post){
            $title = $post->post_title;
            $title = explode('|', $title);
            $image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail');
            $testimonials[] = array(
                'name' => trim($title[0]),
                'title' => trim($title[1]),
                'content' => $post->post_content,
                'image' => $image[0]
            );
        }
        return $testimonials;
    }
}
?>
