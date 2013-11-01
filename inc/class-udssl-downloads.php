<?php
/**
 * UDSSL Download Routing
 */
class UDSSL_Downloads{
    /**
     * Constructor
     */
    function __construct(){
        /**
         * Download Rewrite
         */
        add_action('init', array($this, 'download_rewrite'));

        /**
         * Download Redirect
         */
        add_action('template_redirect', array($this, 'download_redirect'));
    }

    /**
     * UDSSL Download Rewrite
     */
    function download_rewrite(){
        $download = 'downloads/([^&]*)/?$';
        add_rewrite_rule($download, 'index.php?udssl_download=yes&udssl_download_item=$matches[1]', 'top');
        add_rewrite_tag('%udssl_download%', '([^&]+)');
        add_rewrite_tag('%udssl_download_item%', '([^&]+)');
    }

    /**
     * UDSSL Download Redirect
     */
    function download_redirect(){
        global $wp_query;
        if(!isset($wp_query->query_vars['udssl_download']))
            return;

        $download = $wp_query->query_vars['udssl_download'];
        if($download != 'yes')
            return;

        $item = $wp_query->query_vars['udssl_download_item'];
        $file = ABSPATH . 'downloads/' . $item;

        if(!is_readable($file)){
            if('udssl-time-tracker' == $item){
                $url = get_home_url() . '/leave/github.com/UDSSL/time-tracker/archive/v.0.1.zip/';
                wp_redirect($url);
                exit;
            }
            wp_die('UDSSL: Download Error');
        }
    }

}
?>
