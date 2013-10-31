<?php
/**
 * UDSSL Cron
 *
 * wget -q -O – http://local.udssl.dev/wp-cron.php >/dev/null 2>&1
 *
 * Slug: udssl_report
 * Interval: udssl_report_interval
 * Action: udssl_report_action
 */
class UDSSL_Cron{
    /**
     * UDSSL Interval Array
     */
    private $interval_array = array();

    /**
     * Constructor
     */
    function __construct(){
        /**
         * Hook the cron actions to update functions
         */
        add_action('udssl_report_action', array($this, 'udssl_report'));

        /**
         * Add UDSSL Intervals
         */
        $this->add_new_intervals();

        /**
         * Add UDSSL Intervals to WordPress Cron
         */
        add_filter('cron_schedules', array($this, 'udssl_intervals'));
    }

    /**
     * UDSSL Intervals
     */
    function udssl_intervals($schedules){
        $schedules = $schedules + $this->interval_array;
        return $schedules;
    }

    /**
     * Adds a new interval to the UDSSL schedule array
     */
    function add_new_interval( $slug, $period ){
        $interval_name = $slug . '_' . $period;
        $interval_display_name = $slug . '_interval_' . $period;
        $this->interval_array[ $interval_name ] = array(
            'interval'=> $period,
            'display'=>  $interval_display_name
        );
    }

    /**
     * Adds Current UDSSL Intervals
     */
    function add_new_intervals(){
        $options = get_option( 'udssl_options' );

        $slug = 'udssl_report';
        $period = $options['cron']['report']['interval'];
        $interval_name = $slug . '_' . $period;
        $interval_display_name = $slug . '_interval_' . $period;
        $this->interval_array[ $interval_name ] = array(
            'interval'=> $period,
            'display'=>  $interval_display_name
        );
    }

    /**
     * Schedule or Reschedule a new UDSSL Report
     */
    function schedule_event($slug, $period){
        $timestamp = time() + $period;
        $interval_name = $slug . '_' . $period;
        $action = $slug . '_action';

        wp_clear_scheduled_hook($action);

        $this->add_new_interval($slug, $period);

        $r = wp_schedule_event(
            $timestamp,
            $interval_name,
            $action
        );

        if(false === $r){
            global $udssl_theme;
            $udssl_theme->utils->log('error', 'Cron Reschedule Failed.');
        }

        return array(
            'timestamp' => $timestamp,
            'interval_name' => $interval_name,
            'action' => $action
        );
    }

    /**
     * Create Cron Jobs on Activation
     */
    function activation(){
        /**
         * Add Email Report Periodic Update
         */
        $options = get_option('udssl_options');
        $period = $options['cron']['report']['interval'];
        $slug = 'udssl_report';
        $r1 = $this->schedule_event( $slug, $period );

        if(isset( $r1['timestamp'] )):
            return array(
                'r1' => $r1['timestamp'],
            );
        else:
            return false;
        endif;
    }

    /**
     * Get Cron Data
     */
    function get_cron_data($slug){
        $hook = $slug . '_action';
        $next = wp_next_scheduled($hook);
        if($next){
            $now = time();
            $minutes = (int)( ($next - $now) / 60 );
            $seconds = (int)( ($next - $now) % 60 );
            $description = 'Again in ' . $minutes . ' minutes and ' . $seconds . ' seconds';
            return array(
                'last' => 0,
                'next' => $next,
                'description' => $description
            );
        } else {
            return array(
                'last' => 0,
                'next' => 0,
                'description' => 'Not Scheduled'
            );
        }
    }

    /**
     * Clear Cron Jobs on Deactivation
     */
    function deactivation(){
        wp_clear_scheduled_hook('udssl_report_action');
    }

    /**
     * Send Email Report
     */
    function udssl_report(){
        require_once UDS_PATH . 'cron/class-udssl-report.php';
        $reports = new UDSSL_Reports();
        $reports->send_email_report();
    }

}
?>
