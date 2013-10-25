<?php
/**
 * UDSSL Leave
 */
class UDSSL_Leave{
    /**
     * Constructor
     */
    function __construct(){
        /**
         * Leave Rewrite
         */
        add_action('init', array($this, 'leave_rewrite'));

        /**
         * Leave Redirect
         */
        add_action('template_redirect', array($this, 'leave_redirect'));
    }

    /**
     * Leave Rewrite
     */
    function leave_rewrite(){
        $leave = 'leave/(.*)/?$';
        add_rewrite_rule($leave, 'index.php?udssl_leave=$matches[1]', 'top');
        add_rewrite_tag('%udssl_leave%', '([^&]+)');
    }

    /**
     * Leave Redirect
     */
    function leave_redirect(){
        global $wp_query;
        if(!isset($wp_query->query_vars['udssl_leave']))
            return;

        $leave = $wp_query->query_vars['udssl_leave'];
        $url = 'http://' . $leave;
        header('Location:' . $url);
        exit;
    }
}
?>
