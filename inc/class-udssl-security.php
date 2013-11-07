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
            time BIGINT,
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
        $time = current_time('timestamp') + YEAR_IN_SECONDS;
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
        /**
         * User Agent String
         */
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'None';

        /**
         * Operating System Database
         */
        $os_db = array
        (
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        /**
         * Browser Database
         */
        $browser_db = array(
            '/msie/i'       =>  'Internet Explorer',
            '/firefox/i'    =>  'Firefox',
            '/safari/i'     =>  'Safari',
            '/chrome/i'     =>  'Chrome',
            '/opera/i'      =>  'Opera',
            '/netscape/i'   =>  'Netscape',
            '/maxthon/i'    =>  'Maxthon',
            '/konqueror/i'  =>  'Konqueror',
            '/mobile/i'     =>  'Handheld Browser'
        );

        /**
         * Detect Operating System
         */
        $operating_system = 'Unknown';
        foreach ($os_db as $regex => $os) {
            if (preg_match($regex, $user_agent)) {
                $operating_system = $os;
            }
        }

        /**
         * Detect Browser
         */
        $visitor_browser = 'Unknown';
        foreach ($browser_db as $regex => $browser) {
            if (preg_match($regex, $user_agent)) {
                $visitor_browser = $browser;
            }
        }

        $visitor = array(
            'id' => null,
            'time' => current_time('timestamp'),
            'token' => $this->token,
            'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'None',
            'port' => isset($_SERVER['REMOTE_PORT']) ? $_SERVER['REMOTE_PORT'] : '',
            'forwarded_for' => isset($_SERVER['X_FORWARDED_FOR']) ? $_SERVER['X_FORWARDED_FOR'] : 'None',
            'referer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'None',
            'user_agent' => $user_agent,
            'url' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'None',
            'browser' => $visitor_browser,
            'os' => $operating_system,
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

    /**
     * Get Visits
     */
    function get_visits(){
        global $wpdb;
        $start = current_time('timestamp') - DAY_IN_SECONDS;
        $visitor_table = $wpdb->prefix . $this->visitor_table;
        $visits = $wpdb->get_results(
            "
            SELECT *
            FROM $visitor_table
            WHERE time > $start
            ORDER BY time
            DESC
            LIMIT 200
            ", ARRAY_A
        );

        return $visits;
    }

    /**
     * Get Visits Report
     */
    function get_visits_report($last_index, $limit){
        global $wpdb;
        $start = current_time('timestamp') - DAY_IN_SECONDS;
        $visitor_table = $wpdb->prefix . $this->visitor_table;
        $visits = $wpdb->get_results(
            "
            SELECT *
            FROM $visitor_table
            WHERE id > $last_index
            ORDER BY time
            DESC
            LIMIT $limit
            ", ARRAY_A
        );

        return $visits;
    }
}
?>
