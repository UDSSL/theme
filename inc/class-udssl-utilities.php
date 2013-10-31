<?php
/**
 * UDSSL Utitlities
 */
class UDSSL_Utilities{
    /**
     * Admin Email
     */
    private $admin_email = 'praveen.udssl@gmail.com';

    /**
     * Constructor
     */
    function __construct(){
        add_filter('wp_mail_content_type', array($this, 'set_html_content_type'));
    }

    /**
     * HTML Email
     */
    function set_html_content_type() {
        return 'text/html';
    }

    /**
     * Send Email to Admin
     */
    function send_admin_email($subject, $message){
        $r = wp_mail($this->admin_email, $subject, $message);
        return $r;
    }

    /**
     * Log Message
     */
    function log($filename, $message){
        $message = $message . "\n";
        $log = UDS_PATH . 'logs/' . $filename . '.log';
        file_put_contents($log, $message, FILE_APPEND | LOCK_EX);
    }
}
?>
