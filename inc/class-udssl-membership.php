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
         * Login URL
         */
        add_filter( 'login_url', array($this, 'login_url'), 10, 2);

        /**
         * Membership Rewrite
         */
        add_action('init', array($this, 'membership_rewrite'));

        /**
         * Membership Redirect
         */
        add_action('template_redirect', array($this, 'membership_redirect'));

        /**
         * Remove Admin Bar
         */
        add_filter('show_admin_bar', '__return_false');
    }

    /**
     * Login URL
     */
    function login_url( $force_reauth, $redirect ){
        $login_url = get_home_url() . '/login/';

        if ( !empty($redirect) )
            $login_url = add_query_arg( 'redirect_to', urlencode( $redirect ), $login_url );

        if ( $force_reauth )
            $login_url = add_query_arg( 'reauth', '1', $login_url ) ;

        return $login_url ;
    }

    /**
     * UDSSL Membership Rewrite
     */
    function membership_rewrite(){
        $membership = 'membership/?$';
        add_rewrite_rule($membership, 'index.php?udssl_membership=yes', 'top');

        $membership = 'login/?$';
        add_rewrite_rule($membership, 'index.php?udssl_membership=login', 'top');

        $membership = 'logout/?$';
        add_rewrite_rule($membership, 'index.php?udssl_membership=logout', 'top');

        $membership = 'signup/?$';
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
         * UDSSL Membership
         */
        if($query_var == 'yes'):
            /**
             * Die
             */
            wp_die('UDSSL: Not Implemented');

            $this->membership_page();
        endif;

        /**
         * UDSSL Member Login
         */
        if($query_var == 'login'):
            $this->member_login();
        endif;

        /**
         * UDSSL Member Logout
         */
        if($query_var == 'logout'):
            wp_logout();
            wp_redirect(get_home_url());
        endif;

        /**
         * UDSSL Visitor Sign Up
         */
        if($query_var == 'signup'):
            if(is_user_logged_in()){
                wp_redirect(get_home_url());
            } else {
                $res = $this->visitor_signup();
                if ('POST' == $_SERVER['REQUEST_METHOD'] ) {
                    header("HTTP/1.1 200 UDSSL Mail");
                    header('Content-type: application/json');
                    echo json_encode($res);
                }
                exit;
            }
        endif;

        exit;
    }

    /**
     * UDSSL Member
     */
    function membership_page(){
    }

    /**
     * UDSSL Member Login
     */
    function member_login(){
        add_action('wp_enqueue_scripts' , array($this, 'login_enqueue'));;
        if ('POST' == $_SERVER['REQUEST_METHOD'] ) {
            $email = sanitize_text_field($_POST['email']);
            $password = sanitize_text_field($_POST['password']);
            $remember = isset($_POST['remember']) ? true : false;

            /**
             * Verify Nonce
             */
            if(!wp_verify_nonce( $_POST['_wpnonce'], 'udssl_login_form')){
                $_SESSION['login_error'] = '<strong>Error:</strong> Security Check Failed';
                require_once UDS_PATH . 'login.php';
                exit;
            }

            /**
             * Invalid Fields
             */
            if(!is_email($email) || '' == $password ){
                $_SESSION['login_error'] = '<strong>Error:</strong> Invalid Details';
                require_once UDS_PATH . 'login.php';
                exit;
            }

            /**
             * Account associated with email?
             */
            $user = get_user_by('email', $email);
            if(empty($user->user_login)){
                $_SESSION['login_error'] = '<strong>Error:</strong> No account is associated with your email.';
                require_once UDS_PATH . 'login.php';
                exit;
            }

            /**
             * Login User
             */
            $username = $user->user_login;
            $creds = array();
            $creds['user_login'] = $username;
            $creds['user_password'] = $password;
            $creds['remember'] = $remember;
            $user = wp_signon( $creds, false );
            if (is_wp_error($user)){
                $_SESSION['login_error'] = $user->get_error_message();
                require_once UDS_PATH . 'login.php';
                exit;
            }

            $url = get_home_url();
            wp_redirect($url);

        } else if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            unset($_SESSION['login_error']);
            require_once UDS_PATH . 'login.php';
        }
    }

    /**
     * Login Enqueue
     */
    function login_enqueue(){
        wp_enqueue_script( 'jquery-validate', UDS_URL . 'lib/jquery-validate/jquery.validate.min.js', array('udssl-jquery'), false, true );
        wp_enqueue_script( 'udssl-login', UDS_URL . 'js/login.js', array('udssl-jquery', 'jquery-validate'), false, true );
        wp_enqueue_style( 'validate-style', UDS_URL . 'css/validate.css' );
    }

    /**
     * Registration Enqueue
     */
    function registration_enqueue(){
        wp_enqueue_script( 'jquery-validate', UDS_URL . 'lib/jquery-validate/jquery.validate.min.js', array('udssl-jquery'), false, true );
        wp_enqueue_script( 'udssl-registration', UDS_URL . 'js/register.js', array('udssl-jquery', 'jquery-validate'), false, true );
        $udssl_js = array(
          'registration_url' => get_home_url() . '/signup/'
        );
        wp_localize_script('udssl-registration', 'udssl', $udssl_js);
        wp_enqueue_style( 'validate-style', UDS_URL . 'css/validate.css' );
    }

    /**
     * UDSSL Visitor Sign Up
     */
    function visitor_signup(){
        add_action('wp_enqueue_scripts' , array($this, 'registration_enqueue'));;
        if ('POST' == $_SERVER['REQUEST_METHOD'] ) {
            $firstname = sanitize_text_field($_POST['firstname']);
            $lastname = sanitize_text_field($_POST['lastname']);
            $email = sanitize_email($_POST['email']);
            $username = sanitize_text_field($_POST['username']);

            $password = sanitize_text_field($_POST['password']);
            $passwordconfirm = sanitize_text_field($_POST['passwordconfirm']);

            $subscribe = isset($_POST['subscribe']) ? true : false;

            $challenge = sanitize_text_field($_POST['recaptcha_challenge_field']);
            $response = sanitize_text_field($_POST['recaptcha_response_field']);

            /**
             * Verify Nonce
             */
            if(!wp_verify_nonce( $_POST['_wpnonce'], 'udssl_registration_form')){
                $res = array (
                    'response'=>'error',
                    'message' => 'Security Check Failed.'
                );
                return $res;
            }

            /**
             * Invalid Fields
             */
            if(!is_email($email) || '' == $firstname || '' == $lastname
                || '' == $username || '' == $password ){
                $res = array (
                    'response'=>'error',
                    'message' => 'Invalid Fields.'
                );
                return $res;
            }

            /**
             * Match Passwords
             */
            if($password != $passwordconfirm){
                $res = array (
                    'response'=>'error',
                    'message' => 'Passwords Not Matched.'
                );
                return $res;
            }

            /**
             * reCaptcha Check
             */
            global $udssl_theme;
            if(!$udssl_theme->contact->recaptcha_check_answer($challenge, $response)){
                $res = array (
                    'response'=>'error',
                    'message' => 'reCaptcha Error'
                );
                return $res;
            }

            /**
             * Account associated with email?
             */
            $user = get_user_by('email', $email);
            if(!empty($user->user_login)){
                $res = array (
                    'response'=>'error',
                    'message' => 'Email Already in Use.'
                );
                return $res;
            }

            /**
             * Username exists?
             */
            if(username_exists($username)){
                $res = array (
                    'response'=>'error',
                    'message' => 'Username Exists.'
                );
                return $res;
            }

            /**
             * Register User
             */
            $user = array(
                'user_email' => $email,
                'user_login' => $username,
                'first_name' => $firstname,
                'last_name' => $lastname,
                'user_pass' => $password
            );
            $r = wp_insert_user($user);
            if(is_wp_error($r)){
                $res = array (
                    'response'=>'error',
                    'message' => 'Registration Failed.'
                );
                return $res;
            } else {
                $creds = array();
                $creds['user_login'] = $username;
                $creds['user_password'] = $password;
                $creds['remember'] = false;
                $user = wp_signon( $creds, false );
                $res = array (
                    'response'=>'success',
                    'message' => 'Registration Complete.'
                );
                return $res;
            }

        } else if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            unset($_SESSION['registration_error']);
            require_once UDS_PATH . 'register.php';
        }
    }
}
?>
