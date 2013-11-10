<?php
if( !defined( 'ABSPATH' ) ){
    header('HTTP/1.0 403 Forbidden');
    die('No Direct Access Allowed!');
}

$options = get_option('udssl_store_options');
$options = $options['paypal']['paypal_classic'];

/**
 * PayPal Settings Section
 */
add_settings_section(
    'paypal_settings',
    __('PayPal Settings', 'udssl'),
    'paypal_settings_callback',
    'manage-udssl-store');
function paypal_settings_callback(){
    _e('Configure PayPal Settings', 'udssl');
}

/**
 * Username
 */
add_settings_field(
    'username',
    __('Username', 'udssl'),
    'username_callback',
    'manage-udssl-store',
    'paypal_settings',
    $options
);
function username_callback($options){
    echo '<input type="text" name="udssl_store_options[paypal][paypal_classic][username]" class="regular-text" value="' . $options['username'] . '">';
}

/**
 * Password
 */
add_settings_field(
    'password',
    __('Password', 'udssl'),
    'password_callback',
    'manage-udssl-store',
    'paypal_settings',
    $options
);
function password_callback($options){
    echo '<input type="text" name="udssl_store_options[paypal][paypal_classic][password]" class="regular-text" value="' . $options['password'] . '">';
}

/**
 * Signature
 */
add_settings_field(
    'signature',
    __('Signature', 'udssl'),
    'signature_callback',
    'manage-udssl-store',
    'paypal_settings',
    $options
);
function signature_callback($options){
    echo '<input type="text" name="udssl_store_options[paypal][paypal_classic][signature]" class="regular-text" value="' . $options['signature'] . '">';
}

/**
 * Sandbox Mode
 */
add_settings_field(
    'sandbox_mode',
    __('Sandbox Mode', 'udssl'),
    'sandbox_mode_callback',
    'manage-udssl-store',
    'paypal_settings',
    $options
);
function sandbox_mode_callback($options){
    echo '<input type="checkbox" name="udssl_store_options[paypal][paypal_classic][sandbox_mode]" ' . checked($options['sandbox_mode'], true, false) . '">';
    echo ' <lable>Enable Sandbox Mode</lable>';
}

/**
 * Save | Reset.
 */
add_settings_section(
    'save_reset',
    __('Save Settings', 'udssl'),
    'save_reset_callback',
    'manage-udssl-store');

function save_reset_callback(){
    echo '<input name="udssl_store_options[save_paypal]"
        type="submit" class="button-primary" value="' . esc_attr__('Save PayPal Settings', 'udssl') .'" />';
    echo ' <input name="udssl_store_options[reset_paypal]"
        type="submit" class="button-secondary" value="' . esc_attr__('Reset', 'udssl') .'" />';
}
?>
