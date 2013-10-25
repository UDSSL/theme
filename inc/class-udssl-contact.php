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
            /**
             * Die
             */
            wp_die('UDSSL: Not Implemented');

            $this->send_email();
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

        $fields = array(
            0 => array(
                'text' => 'Name',
                'val' => sanitize_text_field($_POST['name'])
            ),
            1 => array(
                'text' => 'Email address',
                'val' => sanitize_email($_POST['email'])
            ),
            2 => array(
                'text' => 'Message',
                'val' => sanitize_text_field($_POST['message'])
            )
        );

        $message = "";

        foreach($fields as $field) {
            $message .= $field['text'].": " . htmlspecialchars($field['val'], ENT_QUOTES) . PHP_EOL;
        }

        if(wp_mail($to, $subject, $message)) {
            $arrResult = array ('response'=>'success');
        } else {
            $arrResult = array ('response'=>'error');
        }

        header("HTTP/1.1 200 UDSSL Mail");
        header('Content-type: application/json');
        echo json_encode($arrResult);
        exit;
    }

    /**
     * Enqueues
     */
    function enqueues(){
        wp_enqueue_script( 'contact-us', UDS_URL . 'js/contact.js', array('jquery-js', 'validate'), false, true );
        $udssl_js = array(
          'contact_url' => get_home_url() . '/contact/'
        );
        wp_localize_script('contact', 'udssl', $udssl_js);
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
}
?>
