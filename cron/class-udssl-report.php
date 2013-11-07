<?php
/**
 * UDSSL Cron Reports
 */
class UDSSL_Reports{
    /**
     * Email Report Test
     */
    function send_email_report_test(){
        global $udssl_theme;
        $subject = 'UDSSL Report via Cron';
        $message = '<h1>Testing</h1>';
        $udssl_theme->utils->send_admin_email($subject, $message);
    }

    /**
     * Email Report
     */
    function send_email_report(){
        $udssl_crons = get_option('udssl_crons');
        if(!$udssl_crons){
            $udssl_crons = array();
        }

        if(!isset($udssl_crons['udssl_report'])){
            $udssl_crons['udssl_report'] = array(
                'last_index' => 0,
                'last_time' => 0
            );
        }

        global $udssl_theme;
        $last_index = $udssl_crons['udssl_report']['last_index'];
        $limit = 100;
        $visits = $udssl_theme->security->get_visits_report($last_index, $limit);

        $total = sizeof($visits);
        if($total > 0){
            $end = $visits[0]['id'];
            $start = $visits[$total -1]['id'];
            $summary = '(' . $start . ' - ' . $end . ') Total: ' . $total;
        } else {
            $end = $last_index;
            $summary = 'No Visits';
        }

        $subject = 'UDSSL Visits: ' . $summary;
        $message = '<p>Last: ' . date('Y-m-d H:i:s', $udssl_crons['udssl_report']['last_time']) . '</p>';
        $message .= '<p>Current: ' . current_time('mysql') . '</p>';

        if($total > 0){
            $td_style = ' width="80" ';
            $message .= '<table cellspacing="0" cellpadding="10" border="0">';
                $message .= '<thead>';
                $message .= '<tr>';
                $message .= '<td' . $td_style . '>ID</td>';
                $message .= '<td' . $td_style . '>Date</td>';
                $message .= '<td' . $td_style . '>Time</td>';
                $message .= '<td' . $td_style . '>Token</td>';
                $message .= '<td' . $td_style . '>IP</td>';
                $message .= '<td' . $td_style . '>Port</td>';
                $message .= '<td' . $td_style . '>Brower</td>';
                $message .= '<td' . $td_style . '>OS</td>';
                $message .= '<td' . $td_style . '>Referer</td>';
                $message .= '<td' . $td_style . '>URL</td>';
                $message .= '<td' . $td_style . '>Visits</td>';
                $message .= '<td' . $td_style . '>User Agent</td>';
                $message .= '<td' . $td_style . '>Forwarded</td>';
                $message .= '</tr>';
                $message .= '</thead>';
                $message .= '<tbody>';
            foreach($visits as $visit){
                $message .= '<tr>';
                $message .= '<td' . $td_style . '>' . $visit['id'] . '</td>';
                $message .= '<td' . $td_style . '>' . date('Y-m-d', $visit['time']) . '</td>';
                $message .= '<td' . $td_style . '>' . date('H:i:s', $visit['time']) . '</td>';
                $message .= '<td' . $td_style . '>' . $visit['token'] . '</td>';
                $message .= '<td' . $td_style . '>' . $visit['ip'] . '</td>';
                $message .= '<td' . $td_style . '>' . $visit['port'] . '</td>';
                $message .= '<td' . $td_style . '>' . $visit['browser'] . '</td>';
                $message .= '<td' . $td_style . '>' . $visit['os'] . '</td>';
                $message .= '<td' . $td_style . '>' . $visit['referer'] . '</td>';
                $message .= '<td' . $td_style . '>' . $visit['url'] . '</td>';
                $message .= '<td' . $td_style . '>' . $visit['visits'] . '</td>';
                $message .= '<td' . $td_style . '>' . $visit['user_agent'] . '</td>';
                $message .= '<td' . $td_style . '>' . $visit['forwarded_for'] . '</td>';
                $message .= '</tr>';
            }
                $message .= '</tbody>';
                $message .= '<tfooter>';
                $message .= '<tr>';
                $message .= '<td' . $td_style . '>ID</td>';
                $message .= '<td' . $td_style . '>Date</td>';
                $message .= '<td' . $td_style . '>Time</td>';
                $message .= '<td' . $td_style . '>Token</td>';
                $message .= '<td' . $td_style . '>IP</td>';
                $message .= '<td' . $td_style . '>Port</td>';
                $message .= '<td' . $td_style . '>Brower</td>';
                $message .= '<td' . $td_style . '>OS</td>';
                $message .= '<td' . $td_style . '>Referer</td>';
                $message .= '<td' . $td_style . '>URL</td>';
                $message .= '<td' . $td_style . '>Visits</td>';
                $message .= '<td' . $td_style . '>User Agent</td>';
                $message .= '<td' . $td_style . '>Forwarded</td>';
                $message .= '</tr>';
                $message .= '</tfooter>';
            $message .= '</table>';
        } else {
            $message .= '<h3>No Visits for the period</h3>';
            $message .= '<p>Last Index: ' . $udssl_crons['udssl_report']['last_index'] . '</p>';
        }

        global $udssl_theme;
        $r = $udssl_theme->utils->send_admin_email($subject, $message);

        //echo $message; $r = true;

        if($r){
            $udssl_crons['udssl_report']['last_index'] = $end;
            $udssl_crons['udssl_report']['last_time'] = time();
            update_option('udssl_crons', $udssl_crons);
        }
    }
}
?>
