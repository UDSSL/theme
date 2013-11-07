<?php
/**
 * Analytics Options
 */
$options = get_option('udssl_analytics_options');

/**
 * Facebook Settings Section
 */
add_settings_section(
    'udssl_visits',
    __('UDSSL Visits', 'udssl'),
    'udssl_visits_callback',
    'udssl-analytics');

/**
 * UDSSL Visits Callback
 */
function udssl_visits_callback(){
    global $udssl_theme;
    $visits = $udssl_theme->security->get_visits();
    $total = sizeof($visits);

    $users = '<p class="description" >Current Time: ' . date('Y-m-d H:i:s');
    $users .= ' (Total Visits: ' . $total . ')</p>';
    $users .= '
        <table class="widefat">
            <thead>
                <tr>
                    <th class="row-title">ID</th>
                    <th>Time</th>
                    <th>Token</th>
                    <th>IP</th>
                    <th>Port</th>
                    <th>Browser</th>
                    <th>OS</th>
                    <th>Referer</th>
                    <th>URL</th>
                    <th>Visits</th>
                    <th>User Agent</th>
                    <th>Forwarded</th>
                </tr>
            </thead>
            <tbody>';

    foreach($visits as $visit){
        $users .= '
                <tr>
                    <td class="row-title">' . $visit['id'] . '</td>
                    <td>' . date('Y-m-d H:i:s', $visit['time']) . '</td>
                    <td>' . $visit['token'] . '</td>
                    <td>' . $visit['ip'] . '</td>
                    <td>' . $visit['port'] . '</td>
                    <td>' . $visit['browser'] . '</td>
                    <td>' . $visit['os'] . '</td>
                    <td>' . $visit['referer'] . '</td>
                    <td>' . $visit['url'] . '</td>
                    <td>' . $visit['visits'] . '</td>
                    <td>' . $visit['user_agent'] . '</td>
                    <td>' . $visit['forwarded_for'] . '</td>
                </tr>
            ';
    }

    $users .= '
            </tbody>
            <tfoot>
                <tr>
                    <th class="row-title">ID</th>
                    <th>Time</th>
                    <th>Token</th>
                    <th>IP</th>
                    <th>Port</th>
                    <th>Browser</th>
                    <th>OS</th>
                    <th>Referer</th>
                    <th>URL</th>
                    <th>Visits</th>
                    <th>User Agent</th>
                    <th>Forwarded</th>
                </tr>
            </tfoot>
        </table>
        ';
    echo $users;
}
?>
