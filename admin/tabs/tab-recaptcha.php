<?php
/**
 * reCaptcha Options
 */
$options = get_option('udssl_options');

/**
 * reCaptcha Settings Section
 */
add_settings_section(
    'recaptcha_configuration',
    __('reCaptcha Configuration', 'udssl'),
    'recaptcha_configuration_callback',
    'manage-udssl');

/**
 * reCaptcha Section Callback
 */
function recaptcha_configuration_callback(){
    _e('Configure the reCaptcha', 'udssl');
}

/**
 * Private Key
 */
add_settings_field(
    'recaptcha_private_key',
    __('reCaptcha Private Key', 'udssl'),
    'recaptcha_private_key_callback',
    'manage-udssl',
    'recaptcha_configuration',
    $options
);

/**
 * Private Key Callback
 */
function recaptcha_private_key_callback($options){
    echo '<input name="udssl_options[recaptcha][private_key]" type="text" class="regular-text" value="' . $options['recaptcha']['private_key'] . '">';
}

/**
 * Public Key
 */
add_settings_field(
    'recaptcha_public_key',
    __('reCaptcha Public Key', 'udssl'),
    'recaptcha_public_key_callback',
    'manage-udssl',
    'recaptcha_configuration',
    $options
);

/**
 * Public Key Callback
 */
function recaptcha_public_key_callback($options){
    echo '<input name="udssl_options[recaptcha][public_key]" type="text" class="regular-text" value="' . $options['recaptcha']['public_key'] . '">';
}

/**
 * Enabled
 */
add_settings_field(
    'recaptcha_enabled',
    __('reCaptcha Enabled', 'udssl'),
    'recaptcha_enabled_callback',
    'manage-udssl',
    'recaptcha_configuration',
    $options
);

/**
 * Enabled Callback
 */
function recaptcha_enabled_callback($options){
    echo '<input name="udssl_options[recaptcha][enabled]" type="checkbox" ' . checked($options['recaptcha']['enabled'], true, false) . '">';
}

/**
 * Save reCaptcha
 */
add_settings_section(
    'save_recaptcha_configuration',
    __('Save Configuration', 'udssl'),
    'save_callback',
    'manage-udssl');

/**
 * Save Settings Callback
 */
function save_callback($options){
    echo '<input name="udssl_options[submit-recaptcha]" type="submit" class="button-primary" value="Save reCaptcha Settings" />';
    echo ' <input name="udssl_options[reset-recaptcha]" type="submit" class="button-secondary" value="Reset" />';
}
?>
