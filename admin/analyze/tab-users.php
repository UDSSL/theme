<?php
/**
 * Facebook Options
 */
$options = get_option('udssl_options');
$options = $options['facebook'];

/**
 * Facebook Settings Section
 */
add_settings_section(
    'facebook_configuration',
    __('Facebook Configuration', 'udssl'),
    'facebook_configuration_callback',
    'manage-udssl');

/**
 * Facebook Section Callback
 */
function facebook_configuration_callback(){
    _e('Configure the Facebook Box', 'udssl');
}

/**
 * App ID
 */
add_settings_field(
    'facebook_app_id',
    __('Facebook ID', 'udssl'),
    'facebook_app_id_callback',
    'manage-udssl',
    'facebook_configuration',
    $options
);

/**
 * App ID Callback
 */
function facebook_app_id_callback($options){
    echo '<input name="udssl_options[facebook_app_id]" type="text" class="regular-text" value="' . $options['facebook_app_id'] . '">';
}

/**
 * Page ID
 */
add_settings_field(
    'facebook_page_id',
    __('Facebook Page ID', 'udssl'),
    'facebook_page_id_callback',
    'manage-udssl',
    'facebook_configuration',
    $options
);

/**
 * Page ID Callback
 */
function facebook_page_id_callback($options){
    echo '<input name="udssl_options[facebook_page_id]" type="text" class="regular-text" value="' . $options['facebook_page_id'] . '">';
}

/**
 * Widget Title
 */
add_settings_field(
    'facebook_widget_title',
    __('Facebook Widget Title', 'udssl'),
    'facebook_widget_title_callback',
    'manage-udssl',
    'facebook_configuration',
    $options
);

/**
 * Widget Title Callback
 */
function facebook_widget_title_callback($options){
    echo '<input name="udssl_options[facebook_widget_title]" type="text" class="regular-text" value="' . $options['facebook_widget_title'] . '">';
}

/**
 * Widget Width
 */
add_settings_field(
    'facebook_widget_width',
    __('Facebook Widget Width', 'udssl'),
    'facebook_widget_width_callback',
    'manage-udssl',
    'facebook_configuration',
    $options
);

/**
 * Widget Width Callback
 */
function facebook_widget_width_callback($options){
    echo '<input name="udssl_options[facebook_widget_width]" type="text" class="regular-text" value="' . $options['facebook_widget_width'] . '">';
}

/**
 * Widget Height
 */
add_settings_field(
    'facebook_widget_height',
    __('Facebook Widget Height', 'udssl'),
    'facebook_widget_height_callback',
    'manage-udssl',
    'facebook_configuration',
    $options
);

/**
 * Widget Height Callback
 */
function facebook_widget_height_callback($options){
    echo '<input name="udssl_options[facebook_widget_height]" type="text" class="regular-text" value="' . $options['facebook_widget_height'] . '">';
}

/**
 * Save Facebook
 */
add_settings_section(
    'save_facebook_configuration',
    __('Save Configuration', 'udssl'),
    'save_callback',
    'manage-udssl');

/**
 * Save Settings Callback
 */
function save_callback($options){
    echo '<input name="udssl_options[submit-facebook]" type="submit" class="button-primary" value="Save Facebook Settings" />';
    echo ' <input name="udssl_options[reset-facebook]" type="submit" class="button-secondary" value="Reset" />';
}
?>
