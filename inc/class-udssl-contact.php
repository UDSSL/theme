<?php
/**
 * UDSSL Contact
 */
class UDSSL_Contact{
    /**
     * UDSSL Contact Constructor
     */
    function __construct(){
        /**
         * Contact Rewrite
         */
        add_action('init', array($this, 'contact_rewrite'));

        /**
         * Contact Redirect
         */
        add_action('template_redirect', array($this, 'contact_redirect'));
    }

    /**
     * UDSSL Contact Rewrite
     */
    function contact_rewrite(){
        $contact = 'contact/?$';
        add_rewrite_rule($contact, 'index.php?udssl_contact=yes', 'top');
        add_rewrite_tag('%udssl_contact%', '([^&]+)');
    }

    /**
     * UDSSL Contact Redirect
     */
    function contact_redirect(){
        global $wp_query;
        if(!isset($wp_query->query_vars['udssl_contact']))
            return;

        $query_var = $wp_query->query_vars['udssl_contact'];
        if($query_var == 'yes'):
            $this->enqueues();
            add_action( 'wp_footer', array($this, 'wp_footer_print'), 77 );
            $this->output_contact_page();
        endif;
        exit;
    }

    /**
     * Output UDSSL Contact Page or Email Message
     */
    function output_contact_page(){
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            $res = $this->send_email();
            header("HTTP/1.1 200 UDSSL Mail");
            header('Content-type: application/json');
            echo json_encode($res);
            exit;
        } else if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            include_once UDS_PATH . 'contact.php';
        }
    }

    /**
     * Send UDSSL Contact Email
     */
    function send_email(){
        $options = get_option('udssl_options');
        $options = $options['contact']['form'];

        $to = $options['email'];

        $subject = 'UDSSL Website Contact Form';

        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $subject = sanitize_text_field($_POST['subject']);
        $message = sanitize_text_field($_POST['message']);
        $challenge = sanitize_text_field($_POST['recaptcha_challenge_field']);
        $response = sanitize_text_field($_POST['recaptcha_response_field']);
        $wpnonce = sanitize_text_field($_POST['wpnonce']);

        /**
         * Verify Nonce
         */
        if(!wp_verify_nonce($wpnonce, 'udssl_contact_form')){
            $res = array (
                'response'=>'error',
                'message' => 'Security Check Failed.'
            );
            return $res;
        }

        /**
         * Required Fields
         */
        if('' == $name || !is_email($email) || '' == $subject || '' == $message
            || '' == $challenge || '' == $response){
            $res = array (
                'response'=>'error',
                'message' => 'Required Fields are Missing'
            );
            return $res;
        }

        /**
         * reCaptcha Check
         */
        if(!$this->recaptcha_check_answer($challenge, $response)){
            $res = array (
                'response'=>'error',
                'message' => 'reCaptcha Error'
            );
            return $res;
        }

        $fields = array(
            0 => array(
                'text' => 'Name',
                'val' => $name
            ),
            1 => array(
                'text' => 'Email address',
                'val' => $email
            ),
            1 => array(
                'text' => 'Subject',
                'val' => $subject
            ),
            2 => array(
                'text' => 'Message',
                'val' => $message
            )
        );

        $message = "";

        foreach($fields as $field) {
            $message .= '<b>' . $field['text'] . '</b>: ' . $field['val'] . '<br />';
        }

        if(wp_mail($to, 'UDSSL Contact Form: ' . $subject, $message)) {
            $res = array (
                'response'=>'success',
                'message' => 'Email Sent Successfully'
            );
            return $res;
        } else {
            $res = array (
                'response'=>'error',
                'message' => 'Email Sending Error'
            );
            return $res;
        }
    }

    /**
     * Enqueues
     */
    function enqueues(){
        wp_enqueue_script( 'jquery-validate', UDS_URL . 'lib/jquery-validate/jquery.validate.min.js', array('udssl-jquery'), false, true );
        wp_enqueue_script( 'contact-us', UDS_URL . 'js/contact.js', array('udssl-jquery', 'jquery-validate'), false, true );
        $udssl_js = array(
          'contact_url' => get_home_url() . '/contact/'
        );
        wp_localize_script('contact-us', 'udssl', $udssl_js);
        wp_enqueue_style( 'validate-style', UDS_URL . 'css/validate.css' );
    }

    /**
     * UDSSL Footer Printer
     */
    function wp_footer_print(){
        $options = get_option('udssl_options');
        $options = $options['contact']['map'];
		$map = '<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
            <script src="' . UDS_URL . 'vendor/jquery.mapmarker.js"></script>

            <script>
                $(document).ready(function() {

                    var mapMarkers = {
                        "markers": [
                            {
                                "latitude": "' . $options['latitude'] . '",
                                "longitude":"' . $options['longitude'] . '",
                                "icon": "' . UDS_URL . 'assets/apple-touch-icon.png"
                            }
                        ]
                    };

                    $("#googlemaps").mapmarker({
                        zoom : ' . $options['zoom'] . ',
                        center: "' . $options['center_x'] . ', ' . $options['center_y'] . '",
                        dragging:1,
                        mousewheel:0,
                        markers: mapMarkers,
                        featureType:"all",
                        visibility: "on",
                        elementType:"geometry"
                    });

                });
            </script>';
        echo $map;
    }

    /**
     * Get reCaptcha HTML
     */
    function get_recaptcha(){
        require_once UDS_PATH . 'lib/recaptcha/recaptchalib.php';
        $options = get_option('udssl_options');
        $publickey = $options['recaptcha']['public_key'];
        return recaptcha_get_html($publickey);
    }

    /**
     * reCaptcha Check Answer
     */
    function recaptcha_check_answer($challenge, $response){
        require_once UDS_PATH . 'lib/recaptcha/recaptchalib.php';
        $options = get_option('udssl_options');
        $privatekey = $options['recaptcha']['private_key'];
        $resp = recaptcha_check_answer ($privatekey,
            $_SERVER["REMOTE_ADDR"],
            $challenge,
            $response);
        if($resp->is_valid){
            return true;
        } else {
            return false;
        }
    }
}
?>
