<?php
if( !defined( 'ABSPATH' ) ){
    header('HTTP/1.0 403 Forbidden');
    die('No Direct Access Allowed!');
}

$options = get_option('udssl_options');

/**
 * Data Section
 */
add_settings_section(
    'data_section',
    __('Configure Data', 'udssl'),
    'data_section_callback',
    'manage-udssl');

/**
 * Form Section
 */
add_settings_section(
    'form_section',
    __('Configure Form', 'udssl'),
    'form_section_callback',
    'manage-udssl');

/**
 * Map Section
 */
add_settings_section(
    'map_section',
    __('Google Map', 'udssl'),
    'map_section_callback',
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
function data_section_callback(){
    _e('Configure Contact Data', 'udssl');
}
function form_section_callback(){
    _e('Configure Contact Form', 'udssl');
}
function map_section_callback(){
    _e('Configure Map', 'udssl');
}
function save_reset_section_callback(){
    echo '<input name="udssl_options[save_contact]"
        type="submit" class="button-primary" value="' . esc_attr__('Save Contact Settings', 'udssl') .'" />';
    echo ' <input name="udssl_options[reset_contact]"
        type="submit" class="button-secondary" value="' . esc_attr__('Reset', 'udssl') .'" />';
}

/**
 * Data Address
 */
add_settings_field(
    'data_address',
    __('Address', 'udssl'),
    'data_address_callback',
    'manage-udssl',
    'data_section',
    $options
);
function data_address_callback($options){
    echo '<input name="udssl_options[contact][data][address]" type="text"
        value="' . $options['contact']['data']['address']. '" class="regular-text" >';
}

/**
 * Data Phone
 */
add_settings_field(
    'data_phone',
    __('Phone', 'udssl'),
    'data_phone_callback',
    'manage-udssl',
    'data_section',
    $options
);
function data_phone_callback($options){
    echo '<input name="udssl_options[contact][data][phone]" type="text"
        value="' . $options['contact']['data']['phone']. '" class="regular-text" >';
}

/**
 * Data Email
 */
add_settings_field(
    'data_email',
    __('Email', 'udssl'),
    'data_email_callback',
    'manage-udssl',
    'data_section',
    $options
);
function data_email_callback($options){
    echo '<input name="udssl_options[contact][data][email]" type="text"
        value="' . $options['contact']['data']['email']. '" class="regular-text" >';
}

/**
 * Data Skype
 */
add_settings_field(
    'data_skype',
    __('Skype', 'udssl'),
    'data_skype_callback',
    'manage-udssl',
    'data_section',
    $options
);
function data_skype_callback($options){
    echo '<input name="udssl_options[contact][data][skype]" type="text"
        value="' . $options['contact']['data']['skype']. '" class="regular-text" >';
}

/**
 * Form Email
 */
add_settings_field(
    'form_email',
    __('Email', 'udssl'),
    'form_email_callback',
    'manage-udssl',
    'form_section',
    $options
);
function form_email_callback($options){
    echo '<input name="udssl_options[contact][form][email]" type="text"
        value="' . $options['contact']['form']['email']. '" class="regular-text" >';
}

/**
 * Map Latitude
 */
add_settings_field(
    'map_latitude',
    __('Latitide', 'udssl'),
    'map_latitude_callback',
    'manage-udssl',
    'map_section',
    $options
);
function map_latitude_callback($options){
    echo '<input name="udssl_options[contact][map][latitude]" type="text"
        value="' . $options['contact']['map']['latitude']. '" class="regular-text" >';
}

/**
 * Map Longitude
 */
add_settings_field(
    'map_longitude',
    __('Latitide', 'udssl'),
    'map_longitude_callback',
    'manage-udssl',
    'map_section',
    $options
);
function map_longitude_callback($options){
    echo '<input name="udssl_options[contact][map][longitude]" type="text"
        value="' . $options['contact']['map']['longitude']. '" class="regular-text" >';
}

/**
 * Map CenterX
 */
add_settings_field(
    'map_center_x',
    __('Center X', 'udssl'),
    'map_center_x_callback',
    'manage-udssl',
    'map_section',
    $options
);
function map_center_x_callback($options){
    echo '<input name="udssl_options[contact][map][center_x]" type="text"
        value="' . $options['contact']['map']['center_x']. '" class="regular-text" >';
}

/**
 * Map CenterY
 */
add_settings_field(
    'map_center_y',
    __('Center Y', 'udssl'),
    'map_center_y_callback',
    'manage-udssl',
    'map_section',
    $options
);
function map_center_y_callback($options){
    echo '<input name="udssl_options[contact][map][center_y]" type="text"
        value="' . $options['contact']['map']['center_y']. '" class="regular-text" >';
}

/**
 * Map Zoom
 */
add_settings_field(
    'map_zoom',
    __('Zoom', 'udssl'),
    'map_zoom_callback',
    'manage-udssl',
    'map_section',
    $options
);
function map_zoom_callback($options){
    echo '<input name="udssl_options[contact][map][zoom]" type="text"
        value="' . $options['contact']['map']['zoom']. '" class="regular-text" >';
}
?>
