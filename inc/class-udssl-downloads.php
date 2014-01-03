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

        /**
         * UDSSL Time Tracker Plugin
         */
        if('udssl-time-tracker' == $item){
            $url = get_home_url() . '/leave/github.com/UDSSL/time-tracker/archive/v.0.1.zip/';
            wp_redirect($url);
            exit;
        }

        /**
         * UDSSL Now Reading Plugin
         */
        if('udssl-now-reading' == $item){
            $url = get_home_url() . '/leave/github.com/UDSSL/now-reading/';
            wp_redirect($url);
            exit;
        }


        global $udssl_theme;
        $item_id = $udssl_theme->store->database->paypal_get_item_id_by_link($item);
        if($item_id){
            $udssl_theme->store->database->decrease_count_by_link($item);
            $this->digital_download($item_id);
        }

        if(!is_user_logged_in()){
            require_once UDS_PATH . 'subscribe.php';
            exit;
        }

        wp_die('UDSSL: Download Error');

    }

    /**
     * UDSSL Digital Download
     */
    function digital_download($item_id){
        $product_data = get_post_meta($item_id, 'product_data', true);
        $file = ABSPATH . 'udssl_dd/' . $product_data['filename'];
        if(is_readable($file)){
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $product_data['filename']);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }
        wp_die('UDSSL: Download Error');
    }

}
?>
