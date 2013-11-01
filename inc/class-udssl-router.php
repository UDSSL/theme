<?php
/**
 * UDSSL URL Routing
 */
class UDSSL_Router{
    /**
     * Constructor
     */
    function __construct(){
        /**
         * Router Rewrite
         */
        add_action('init', array($this, 'router_rewrite'));

        /**
         * Router Redirect
         */
        add_action('template_redirect', array($this, 'router_redirect'));
    }

    /**
     * UDSSL Router Rewrite
     */
    function router_rewrite(){
        $router = 'praveen-chathuranga-dias/?$';
        add_rewrite_rule($router, 'index.php?udssl_router=praveen-chathuranga-dias', 'top');

        $router = 'donate/?$';
        add_rewrite_rule($router, 'index.php?udssl_router=donate', 'top');

        add_rewrite_tag('%udssl_router%', '([^&]+)');
    }

    /**
     * UDSSL Router Redirect
     */
    function router_redirect(){
        global $wp_query;
        if(!isset($wp_query->query_vars['udssl_router']))
            return;

        $route = $wp_query->query_vars['udssl_router'];
        if('praveen-chathuranga-dias' == $route){
            $url = get_home_url() . '/leave/www.facebook.com/praveenchathuranagadias/';
        } else if('donate' == $route){
            $url = get_home_url() . '/leave/wordpressfoundation.org/donate/';
        } else {
            $url = get_home_url();
        }
        wp_redirect($url);
    }
}
?>
