<?php
/**
 * Cron Options
 */
$options = get_option('udssl_options');
$options = $options['cron'];

/**
 * Cron Settings Section
 */
add_settings_section(
    'cron_configuration',
    __('Cron Configuration', 'udssl'),
    'cron_configuration_callback',
    'manage-udssl');

/**
 * Cron Section Callback
 */
function cron_configuration_callback(){
    _e('Configure UDSSL Cron', 'udssl');
}

/**
 * Cron Interval
 */
add_settings_field(
    'cron_interval',
    __('Cron Interval', 'udssl'),
    'cron_interval_callback',
    'manage-udssl',
    'cron_configuration',
    $options
);

/**
 * Cron Interval Callback
 */
function cron_interval_callback($options){
    echo '<input name="udssl_options[report][interval]" type="text" class="regular-text" value="' . $options['report']['interval']. '">';
}

/**
 * Save Cron
 */
add_settings_section(
    'save_cron_configuration',
    __('Save Configuration', 'udssl'),
    'save_callback',
    'manage-udssl');

/**
 * Save Settings Callback
 */
function save_callback($options){
    echo '<input name="udssl_options[submit-cron]" type="submit" class="button-primary" value="Save Cron Configuration" />';
    echo ' <input name="udssl_options[reset-cron]" type="submit" class="button-secondary" value="Reset" />';
}

/**
 * Cron Data
 */
add_settings_section(
    'cron_data',
    __('Current Cron Configuration', 'udssl'),
    'cron_data_callback',
    'manage-udssl');

/**
 * Cron Data Callback
 */
function cron_data_callback($options){
    $crons = array();
    global $udssl_theme;
    $hook = 'udssl_report';
    $crons[$hook] = $udssl_theme->cron->get_cron_data($hook);
    $users = '
        <table class="widefat">
            <thead>
                <tr>
                    <th class="row-title">Hook</th>
                    <th>Last Run</th>
                    <th>Next</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>';

    foreach($crons as $hook => $data){
        $users .= '
                <tr>
                    <td class="row-title">' . $hook . '</td>
                    <td>' . date('Y-m-d H:i:s', $data['last']) . '</td>
                    <td>' . date('Y-m-d H:i:s', $data['next']) . '</td>
                    <td>' . $data['description'] . '</td>
                </tr>
            ';
    }

    $users .= '
            </tbody>
            <tfoot>
                <tr>
                    <th class="row-title">Hook</th>
                    <th>Last Run</th>
                    <th>Next</th>
                    <th>Description</th>
                </tr>
            </tfoot>
        </table>
        ';
    $users .= '<p class="description" >Current Time: ' . date('Y-m-d H:i:s') . '</p>';
    echo $users;
}
?>
