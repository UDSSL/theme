<?php
/**
 * UDSSL Membership
 */
class UDSSL_Membership{
    /**
     * UDSSL Membership Constructor
     */
    function __construct(){
        /**
         * Membership Rewrite
         */
        add_action('init', array($this, 'membership_rewrite'));

        /**
         * Membership Redirect
         */
        add_action('template_redirect', array($this, 'membership_redirect'));
    }

    /**
     * UDSSL Membership Rewrite
     */
    function membership_rewrite(){
        $membership = 'membership/?$';
        add_rewrite_rule($membership, 'index.php?udssl_membership=yes', 'top');

        $membership = 'sign-in/?$';
        add_rewrite_rule($membership, 'index.php?udssl_membership=signin', 'top');

        $membership = 'sign-up/?$';
        add_rewrite_rule($membership, 'index.php?udssl_membership=signup', 'top');

        add_rewrite_tag('%udssl_membership%', '([^&]+)');
    }

    /**
     * UDSSL Membership Redirect
     */
    function membership_redirect(){
        global $wp_query;
        if(!isset($wp_query->query_vars['udssl_membership']))
            return;

        $query_var = $wp_query->query_vars['udssl_membership'];

        /**
         * Die
         */
        wp_die('UDSSL: Not Implemented');

        /**
         * UDSSL Membership
         */
        if($query_var == 'yes'):
            $this->membership_page();
        endif;

        /**
         * UDSSL Member Sign In
         */
        if($query_var == 'signin'):
            $this->member_sign_in();
        endif;

        /**
         * UDSSL Member Sign Up
         */
        if($query_var == 'signup'):
            $this->member_sign_up();
        endif;

        exit;
    }

    /**
     * UDSSL Member
     */
    function membership_page(){
    }

    /**
     * UDSSL Member Sign In
     */
    function member_sign_in(){
    }

    /**
     * UDSSL Member Sign Up
     */
    function member_sign_up(){
    }
}
?>
