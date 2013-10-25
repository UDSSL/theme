<?php
if( !defined( 'ABSPATH' ) ){
    header('HTTP/1.0 403 Forbidden');
    die('No Direct Access Allowed!');
}

$options = get_option('udssl_options');

/**
 * Newsletter Section
 */
add_settings_section(
    'newsletter_section',
    __('Configure Mailchimp', 'udssl'),
    'newsletter_section_callback',
    'manage-udssl');

/**
 * Save Reset Section
 */
add_settings_section(
    'save_reset_section',
    __('Save Basic Settings', 'udssl'),
    'save_reset_section_callback',
    'manage-udssl');

/**
 * Section Headings
 */
function newsletter_section_callback(){
    _e('Configure Newsletter', 'udssl');
}
function save_reset_section_callback(){
    echo '<input name="udssl_options[save_newsletter]"
        type="submit" class="button-primary" value="' . esc_attr__('Save Newsletter Settings', 'udssl') .'" />';
    echo ' <input name="udssl_options[reset_newsletter]"
        type="submit" class="button-secondary" value="' . esc_attr__('Reset', 'udssl') .'" />';
}

/**
 * API Key
 */
add_settings_field(
    'api_key',
    __('API Key', 'udssl'),
    'api_key_callback',
    'manage-udssl',
    'newsletter_section',
    $options
);
function api_key_callback($options){
    echo '<input name="udssl_options[newsletter][apikey]" type="text"
        value="' . $options['newsletter']['apikey']. '" class="regular-text" >';
}

/**
 * List ID
 */
add_settings_field(
    'list_id',
    __('List ID', 'udssl'),
    'list_id_callback',
    'manage-udssl',
    'newsletter_section',
    $options
);
function list_id_callback($options){
    echo '<input name="udssl_options[newsletter][id]" type="text"
        value="' . $options['newsletter']['id']. '" class="regular-text" >';
}

/**
 * URL
 */
add_settings_field(
    'url',
    __('Mailchimp Endpoint', 'udssl'),
    'url_callback',
    'manage-udssl',
    'newsletter_section',
    $options
);
function url_callback($options){
    echo '<input name="udssl_options[newsletter][url]" type="text"
        value="' . $options['newsletter']['url']. '" class="regular-text" >';
}

/**
 * Default Name
 */
add_settings_field(
    'default_name',
    __('Default Name', 'udssl'),
    'default_name_callback',
    'manage-udssl',
    'newsletter_section',
    $options
);
function default_name_callback($options){
    echo '<input name="udssl_options[newsletter][default_name]" type="text"
        value="' . $options['newsletter']['default_name']. '" class="regular-text" >';
}
?>
