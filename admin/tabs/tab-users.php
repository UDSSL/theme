<?php
/**
 * Users Options
 */
$options = get_option('udssl_options');
$options = $options['users'];

/**
 * Login Settings Section
 */
add_settings_section(
    'login_configuration',
    __('Login Configuration', 'udssl'),
    'login_configuration_callback',
    'manage-udssl');

/**
 * Login Section Callback
 */
function login_configuration_callback(){
    _e('Configure User Login Options', 'udssl');
}

/**
 * Login Expiration Time
 */
add_settings_field(
    'login_expiration_time',
    __('Login Expiration Time', 'udssl'),
    'login_expiration_time_callback',
    'manage-udssl',
    'login_configuration',
    $options
);

/**
 * Login Expiration Time Callback
 */
function login_expiration_time_callback($options){
    echo '<input name="udssl_options[users][login_expiration_time]" type="text" class="small-text" value="' . $options['login_expiration_time'] . '">';
    echo ' <span class="description" >Measured from the last access time. in minutes.</span>';
}

/**
 * Session Take Over
 */
add_settings_field(
    'take_over',
    __('Take Over Session', 'udssl'),
    'take_over_callback',
    'manage-udssl',
    'login_configuration',
    $options
);

/**
 * Taking of the Session Callback
 */
function take_over_callback($options){
    echo '<input name="udssl_options[users][take_over]" type="checkbox"' . checked($options['take_over'], true, false) . '">';
    echo ' <span class="description" >Invalidate previous session on new user login.</span>';
}

/**
 * Save Users
 */
add_settings_section(
    'save_users_configuration',
    __('Save Configuration', 'udssl'),
    'save_callback',
    'manage-udssl');

/**
 * Save Settings Callback
 */
function save_callback($options){
    echo '<input name="udssl_options[submit-users]" type="submit" class="button-primary" value="Save Users Settings" />';
    echo ' <input name="udssl_options[reset-users]" type="submit" class="button-secondary" value="Reset" />';
}

/**
 * Currently Logged-in Users
 */
add_settings_section(
    'current_users',
    __('Currently Logged-in Users', 'udssl'),
    'current_users_callback',
    'manage-udssl');

/**
 * Currently Logged-in Users Callback
 */
function current_users_callback($options){
    $logged_in = get_option('udssl_logged_in_users');
    if(false == $logged_in){
        $logged_in = array();
    }
    $users = '
        <table class="widefat">
            <thead>
                <tr>
                    <th class="row-title">User ID</th>
                    <th>User Login</th>
                    <th>IP</th>
                    <th>Login Time</th>
                    <th>Last Accessed</th>
                    <th>Session ID</th>
                    <th>Session State</th>
                    <th>Elapsed Time</th>
                    <th>Logout</th>
                </tr>
            </thead>
            <tbody>';

    foreach($logged_in as $user_id => $user){
        $elapsed_time = time() - $user['access_time'];
        $options = get_option('udssl_options');
        $expiration_time = $options['users']['login_expiration_time'] * 60;
        $state = $elapsed_time > $expiration_time ? 'Expired' : 'Active';
        $elapsed = round($elapsed_time/60);
        $button_title = 'Logout ' . $user['user_login'];
        $logout = '<input name="udssl_options[logout_' . $user_id . ']" type="submit" title="' . $button_title . '" class="button-secondary" value="Logout" />';

        $users .= '
                <tr>
                    <td class="row-title">' . $user_id . '</td>
                    <td>' . $user['user_login'] . '</td>
                    <td>' . $user['ip'] . '</td>
                    <td>' . date('Y-m-d H:i:s', $user['login_time']) . '</td>
                    <td>' . date('Y-m-d H:i:s', $user['access_time']) . '</td>
                    <td>' . $user['udssl_session'] . '</td>
                    <td>' . $state . '</td>
                    <td>' . $elapsed . '</td>
                    <td>' . $logout . '</td>
                </tr>
            ';
    }

    $users .= '
            </tbody>
            <tfoot>
                <tr>
                    <th class="row-title">User ID</th>
                    <th>User Login</th>
                    <th>IP</th>
                    <th>Login Time</th>
                    <th>Last Accessed</th>
                    <th>Session ID</th>
                    <th>Session State</th>
                    <th>Elapsed Time</th>
                    <th>Logout</th>
                </tr>
            </tfoot>
        </table>
        ';
    $users .= '<p class="description" >Current Time: ' . date('Y-m-d H:i:s') . '</p>';
    echo $users;
}
?>
