<?php
/**
 * UDSSL Editor
 */
class UDSSL_Editor{
    /**
     * Constructor
     */
    function __construct(){
        /**
         * Remove WYSWYG Editor
         */
        add_action('init', array($this, 'remove_wysiwyg'));

        /**
         * Remove Auto P
         */
        remove_filter('the_content', 'wpautop');

    }

    /**
     * Remove WYSWYG Editor
     */
    function remove_wysiwyg(){
        add_filter('user_can_richedit', '__return_false');
    }
}
?>
