<?php
/**
 * UDSSL SEO
 */
class UDSSL_SEO{
    /**
     * Constructor
     */
    function __construct(){
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));

        add_action('save_post', array($this, 'save_meta_boxes'));
    }

    /**
     * Add SEO Boxes
     */
    function add_meta_boxes(){
        $post_types = array('news', 'udssl-time-tracker', 'post', 'projects', 'page', 'products');

        foreach($post_types as $post_type){
            add_meta_box(
                'seo_box',
                __('SEO Box', 'udssl'),
                array($this, 'seo_box_cb'),
                $post_type,
                'normal',
                'high'
            );
        }
    }

    /**
     * SEO Meta Box
     */
    function seo_box_cb($post){
        wp_nonce_field('seo_meta_save', 'seo_meta_data');
        $seo = get_post_meta($post->ID, 'seo', true);
        if(!$seo){
            $seo = array(
                'title' => '',
                'description' => '',
                'noindex' => false
            );
        }

        $output = '<table style="width: 100%; max-width: 100%;" ><tr><td width="20%" ><label>' . __('SEO Title', 'udssl') . '</label></td>';
        $output .= "<td><input type='text' class='large-text' name='seo_title' value='" . $seo['title'] . "' ></td></tr>";

        $output .= '<tr><td><label>' . __('SEO Description', 'udssl') . '</label></td>';
        $output .= "<td><textarea class='large-text' name='seo_description' >" . $seo['description'] . "</textarea>";

        $output .= '<tr><td><label>' . __('NoIndex', 'udssl') . '</label></td>';
        $output .= "<td><input type='checkbox' name='seo_noindex' " . checked($seo['noindex'], true, false) . "' ></td></tr>";
        $output .= "</table>";
        echo $output;
    }


    /**
     * Save SEO Values
     */
    function save_meta_boxes($post_id){
        /**
         * Return on autosave.
         */
        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        /**
         * Nonce check.
         */
        if(!isset($_POST['seo_meta_data']) || !wp_verify_nonce($_POST['seo_meta_data'], 'seo_meta_save'))
            return;

        /**
         * Permission check.
         */
        if(!current_user_can('edit_post', $post_id ))
            return;

        /**
         * Collect SEO Information
         */
        $seo['title'] = sanitize_text_field($_POST['seo_title']);
        $seo['description'] = sanitize_text_field($_POST['seo_description']);
        $seo['noindex'] = isset($_POST['seo_noindex']) ? true : false;

        update_post_meta($post_id, 'seo', $seo );
    }
}
?>
