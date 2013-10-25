<?php
/**
 * UDSSL Security
 */
class UDSSL_Security{
    /**
     * Visitor Table
     */
    private $visitor_table = 'visitors';

    /**
     * Token
     */
    public $token;

    /**
     * Visits
     */
    public $visits;

    /**
     * New Visitor
     */
    public $new_visitor = true;

    /**
     * Construct
     */
    function __construct(){
        add_action('init', array($this, 'check_visitor'));
    }

    /**
     * Create Visitor Table
     */
    function create_visitor_table(){
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $visitor_table = $wpdb->prefix . $this->visitor_table;

        $visitor_sql = "CREATE TABLE IF NOT EXISTS $visitor_table (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            time DATETIME,
            token BIGINT DEFAULT 0,
            ip VARCHAR(80),
            port VARCHAR(20),
            forwarded_for VARCHAR(80),
            referer VARCHAR(400),
            user_agent VARCHAR(400),
            url VARCHAR(400),
            browser VARCHAR(400),
            os VARCHAR(50),
            visits INT DEFAULT 0
        )
        engine = InnoDB
        default character set = utf8
        collate = utf8_unicode_ci;";

        $result = dbDelta($visitor_sql);

        if (!$result) {
            $message  = 'Invalid query: ' . mysql_error() . "\n";
            $message .= 'Whole query: ' . $visitor_sql;
            die($message);
        }
        return true;
    }

    /**
     * Check Visitor
     */
    function check_visitor(){
        if(!is_user_logged_in()){
            $this->set_cookie();
            $this->log();
        }
    }

    /**
     * Set Cookie
     */
    function set_cookie(){
        $time = time()+60*60*24*365;
        if(isset($_COOKIE['udssl_token']) && isset($_COOKIE['udssl_visits'])){
            $this->new_visitor = false;
            $this->token = $_COOKIE['udssl_token'];
            $this->visits = $_COOKIE['udssl_visits'] + 1;
            setcookie('udssl_visits', $_COOKIE['udssl_visits'] + 1, $time);
        } else {
            $this->cookie = date('YmdHis') . rand(1000,9999);
            setcookie('udssl_token', $this->cookie, $time);
            setcookie('udssl_visits', 1, $time);
        }
    }

    /**
     * Log Visitor
     */
    function log(){
        $visitor = array(
            'id' => null,
            'time' => date('Y-m-d H:i:s'),
            'token' => $this->token,
            'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'None',
            'port' => isset($_SERVER['REMOTE_PORT']) ? $_SERVER['REMOTE_PORT'] : '',
            'forwarded_for' => isset($_SERVER['X_FORWARDED_FOR']) ? $_SERVER['X_FORWARDED_FOR'] : 'None',
            'referer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'None',
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'None',
            'url' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'None',
            'browser' => '',
            'os' => '',
            'visits' => $this->visits
        );

        $this->add_visitor($visitor);
    }

    /**
     * Add Visitor Record
     */
    function add_visitor($visitor){
        global $wpdb;
        $visitor_table = $wpdb->prefix . $this->visitor_table;

        $time = $visitor['time'];
        $token = mysql_real_escape_string($visitor['token']);
        $ip = mysql_real_escape_string($visitor['ip']);
        $port = mysql_real_escape_string($visitor['port']);
        $forwarded_for = mysql_real_escape_string($visitor['forwarded_for']);
        $referer = mysql_real_escape_string($visitor['referer']);
        $user_agent = mysql_real_escape_string($visitor['user_agent']);
        $url = mysql_real_escape_string($visitor['url']);
        $browser = mysql_real_escape_string($visitor['browser']);
        $os = mysql_real_escape_string($visitor['os']);
        $visits = (int) $visitor['visits'];

        $new_visitor = "INSERT INTO $visitor_table VALUES (
            null,
            '$time',
            '$token',
            '$ip',
            '$port',
            '$forwarded_for',
            '$referer',
            '$user_agent',
            '$url',
            '$browser',
            '$os',
            '$visits'
        )";
        $result = mysql_query($new_visitor);

        if (!$result) {
            $message  = 'Invalid query: ' . mysql_error() . "\n";
            $message .= 'Whole query: ' . $new_visitor;
            die($message);
        } else {
            return true;
        }
    }
}
?>
