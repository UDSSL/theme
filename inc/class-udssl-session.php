<?php
/**
 * UDSSL Sessions
 */
class UDSSL_Session{
    /**
     * Constructor
     */
    function __construct(){
        add_action('init', array($this, 'session_init'));
        add_action('init', array($this, 'check_logged_in_user'), 20, 1);

        add_action('set_auth_cookie', array($this, 'set_udssl_cookie'), 10, 5);
        add_action('auth_redirect', array($this, 'check_user'), 20, 1);
        add_action('clear_auth_cookie', array($this, 'logout'));

        add_action('wp_login', array($this, 'handle_expired_logins'), 70, 2);
        add_action('wp_login', array($this, 'login_check'), 80, 2);
        add_action('wp_login', array($this, 'login'), 90, 2);
    }

    function session_init(){
        session_start();
        $session_id = session_id();
    }

    /**
     * Set UDSSL Cookie
     */
    function set_udssl_cookie($auth_cookie, $expire, $expiration, $user_id, $scheme){
        $udssl_session = rand(100, 999) . time() . rand(1000, 9999);
        $_SESSION['udssl_session'] = $udssl_session;
    }

    /**
     * Handle Expired Logins
     */
    function handle_expired_logins($user_login, $user){
        $logged_in = get_option('udssl_logged_in_users');
        if(isset($logged_in[$user->ID])){
            $options = get_option('udssl_options');
            $expiration_time = $options['users']['login_expiration_time'] * 60;
            $elapsed_time = time() - $logged_in[$user->ID]['access_time'];
            if($elapsed_time > $expiration_time){
                $_SESSION['is_expired'] = true;
                return true;
            }
        }
        $_SESSION['is_expired'] = false;
    }

    /**
     * Check on Login
     */
    function login_check($user_login, $user){
        if($_SESSION['is_expired']){
            return true;
        }

        $logged_in = get_option('udssl_logged_in_users');
        if(isset($logged_in[$user->ID])){
            $options = get_option('udssl_options');
            /**
             * Don't Take Over the Session
             */
            if(!$options['users']['take_over']){
                wp_logout();
                wp_die('UDSSL: Already Logged In (' . $logged_in[$user->ID]['ip'] . ')');
            }
        }
    }

    /**
     * UDSSL Login
     */
    function login($user_login, $user){
        $logged_in = get_option('udssl_logged_in_users');
        if(false == $logged_in){
            $logged_in = array();
        }

        /**
         * Update Logged-in user array
         */
        $logged_in[$user->ID] = array(
            'user_login' => $user_login,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'login_time' => time(),
            'access_time' => time(),
            'udssl_session' => $_SESSION['udssl_session']
        );
        update_option('udssl_logged_in_users', $logged_in);
    }

    function check_logged_in_user(){
        if(is_user_logged_in()){
            $this->check_user(get_current_user_id());
        }
    }

    /**
     * Check User
     */
    function check_user($user_id){
        /**
         * Check Logged-in Option Array Entry
         */
        $logged_in = get_option('udssl_logged_in_users');
        if(!isset($logged_in[$user_id])){
            $_SESSION['logout_method'] = 'remove';
            wp_logout();
            wp_die('UDSSL: No Valid Login Record');
        }

        /**
         * Check IP
         */
        $login_ip = $logged_in[$user_id]['ip'];
        $visiting_ip = $_SERVER['REMOTE_ADDR'];
        if($login_ip != $visiting_ip){
            $_SESSION['logout_method'] = 'noremove';
            wp_logout();
            wp_die('UDSSL: Login IP != Visiting IP');
        }

        /**
         * Check Session ID
         */
        $udssl_session = $logged_in[$user_id]['udssl_session'];
        $visitor_session = $_SESSION['udssl_session'];
        if($udssl_session != $visitor_session){
            $_SESSION['logout_method'] = 'noremove';
            wp_logout();
            wp_die('UDSSL: Invalid Session');
        }

        /**
         * Update Last Access Time
         */
        $logged_in[$user_id]['access_time'] = time();
        update_option('udssl_logged_in_users', $logged_in);
        return true;
    }

    /**
     * UDSSL Logout
     */
    function logout(){
        if( !isset($_SESSION['logout_method']) ||
            (isset($_SESSION['logout_method']) && 'noremove' != $_SESSION['logout_method']) ){
            $logged_in = get_option('udssl_logged_in_users');
            if(false == $logged_in){
                $logged_in = array();
            }
            $current_user_id = get_current_user_id();
            if(isset($logged_in[$current_user_id])){
                unset($logged_in[$current_user_id]);
            }
            update_option('udssl_logged_in_users', $logged_in);
        }
    }
}
?>
