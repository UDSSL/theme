<?php
/**
 * UDSSL Integration
 */
class UDSSL_Mailchimp{
    /**
     * Constructor
     */
    function __construct(){
        /**
         * Mailchimp Rewrite
         */
        add_action('init', array($this, 'mailchimp_rewrite'));

        /**
         * Mailchimp Redirect
         */
        add_action('template_redirect', array($this, 'mailchimp_redirect'));
    }

    /**
     * Mailchimp Rewrite
     */
    function mailchimp_rewrite(){
        $mailchimp = 'mailchimp-subscribe/?$';
        add_rewrite_rule($mailchimp, 'index.php?udssl_mailchimp=yes', 'top');
        add_rewrite_tag('%udssl_mailchimp%', '([^&]+)');
    }

    /**
     * Mailchimp Redirect
     */
    function mailchimp_redirect(){
        global $wp_query;
        if(!isset($wp_query->query_vars['udssl_mailchimp']))
            return;

        $query_var = $wp_query->query_vars['udssl_mailchimp'];
        if($query_var == 'yes'):
            $this->mailchimp_subscribe();
        endif;
        exit;
    }

    /**
     * Mailchimp Subscribe
     */
    function mailchimp_subscribe(){
        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            header("HTTP/1.1 405 UDSSL Needs You to POST");
            echo 'Method Not Allowed';
            exit;
        } else if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            /**
             * Die
             */
            wp_die('UDSSL: Not Implemented');

            $options = get_option('udssl_options');
            $options = $options['newsletter'];

            $email = sanitize_email($_POST['email']);
            $merge_vars = array( 'YNAME' => $options['default_name']);

            $apikey = $options['apikey'];

            $body = array(
                'apikey' => $apikey,
                'id' => $options['id'],
                'email' => array(
                    'email' => $email,
                    'euid' => 'udssl' . time(),
                    'leid' => 'website_' . time()
                ),
                'merge_vars' => $merge_vars
            );

            $url = $options['url'];

            $dc = "us1";
            if (strstr($apikey,"-")){
                list($key, $dc) = explode("-",$apikey,2);
                if (!$dc) $dc = "us1";
            }
            $url = str_replace('https://api', 'https://'.$dc.'.api', $url);

            $response = wp_remote_post( $url, array(
                'method' => 'POST',
                'headers' => array('Content-Type: application/json'),
                'body' => json_encode($body),
                'double_optin' => false,
                'welcome_email' => false
                )
            );

            if (200 == $response['response']['code']) {
                $arrResult = array ('response'=>'success');
            } else {
                $arrResult = array ('response'=>'error');
            }

            header("HTTP/1.1 200 UDSSL Mailchimp");
            header('Content-type: application/json');
            echo json_encode($arrResult);
            exit;
        }
    }
}
?>
