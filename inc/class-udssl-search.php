<?php
/**
 * UDSSL Search
 */
class UDSSL_Search{
    /**
     * UDSSL Search Constructor
     */
    function __construct(){
        /**
         * Search Rewrite
         */
        add_action('init', array($this, 'search_rewrite'));

        /**
         * Search Redirect
         */
        add_action('template_redirect', array($this, 'search_redirect'));
    }

    /**
     * UDSSL Search Rewrite
     */
    function search_rewrite(){
        $search = 'search/?$';
        add_rewrite_rule($search, 'index.php?udssl_search=yes', 'top');
        add_rewrite_tag('%udssl_search%', '([^&]+)');
    }

    /**
     * UDSSL Search Redirect
     */
    function search_redirect(){
        global $wp_query;
        if(!isset($wp_query->query_vars['udssl_search']))
            return;

        $query_var = $wp_query->query_vars['udssl_search'];

        /**
         * Die
         */
        wp_die('UDSSL: Not Implemented');

        /**
         * UDSSL Search
         */
        if($query_var == 'yes'):
            $this->search_page();
        endif;

        exit;
    }

    /**
     * UDSSL Search
     */
    function search_page(){
    }
}
?>
