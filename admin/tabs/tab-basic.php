<?php
if( !defined( 'ABSPATH' ) ){
    header('HTTP/1.0 403 Forbidden');
    die('No Direct Access Allowed!');
}

$options = get_option('udssl_options');

/**
 * Facebook Section
 */
add_settings_section(
    'facebook_section',
    __('Facebook', 'udssl'),
    'facebook_section_callback',
    'manage-udssl');

/**
 * Twitter Section
 */
add_settings_section(
    'twitter_section',
    __('Twitter', 'udssl'),
    'twitter_section_callback',
    'manage-udssl');

/**
 * LinkedIn Section
 */
add_settings_section(
    'linkedin_section',
    __('LinkedIn', 'udssl'),
    'linkedin_section_callback',
    'manage-udssl');

/**
 * Google Section
 */
add_settings_section(
    'google_section',
    __('Google', 'udssl'),
    'google_section_callback',
    'manage-udssl');

/**
 * StackOverflow Section
 */
add_settings_section(
    'stackexchange_section',
    __('StackOverflow', 'udssl'),
    'stackexchange_section_callback',
    'manage-udssl');

/**
 * YouTube Section
 */
add_settings_section(
    'youtube_section',
    __('YouTube', 'udssl'),
    'youtube_section_callback',
    'manage-udssl');

/**
 * GitHub Section
 */
add_settings_section(
    'github_section',
    __('GitHub', 'udssl'),
    'github_section_callback',
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
function facebook_section_callback(){
    _e('Configure Facebook', 'udssl');
}
function twitter_section_callback(){
    _e('Configure Twitter', 'udssl');
}
function linkedin_section_callback(){
    _e('Configure LinkedIn', 'udssl');
}
function google_section_callback(){
    _e('Configure Google', 'udssl');
}
function stackexchange_section_callback(){
    _e('Configure StackExchange', 'udssl');
}
function youtube_section_callback(){
    _e('Configure YouTube', 'udssl');
}
function github_section_callback(){
    _e('Configure GitHub', 'udssl');
}
function save_reset_section_callback(){
    echo '<input name="udssl_options[save_basic]"
        type="submit" class="button-primary" value="' . esc_attr__('Save Basic Settings', 'udssl') .'" />';
    echo ' <input name="udssl_options[reset_basic]"
        type="submit" class="button-secondary" value="' . esc_attr__('Reset', 'udssl') .'" />';
}

/**
 * Facebook URL
 */
add_settings_field(
    'facebook_url',
    __('Facebook URL', 'udssl'),
    'facebook_url_callback',
    'manage-udssl',
    'facebook_section',
    $options
);
function facebook_url_callback($options){
    echo '<input name="udssl_options[basic][facebook][url]" type="text"
        value="' . $options['basic']['facebook']['url']. '" class="regular-text" >';
    echo '<p class="description" >' . __('Your Facebook URL', 'udssl') . '</p>';
}

/**
 * Twitter Username
 */
add_settings_field(
    'twitter_username',
    __('Twitter Username', 'udssl'),
    'twitter_username_callback',
    'manage-udssl',
    'twitter_section',
    $options
);
function twitter_username_callback($options){
    echo '<input name="udssl_options[basic][twitter][user_name]" type="text"
        value="' . $options['basic']['twitter']['user_name']. '" class="regular-text" >';
    echo '<p class="description" >' . __('Your Twitter Username', 'udssl') . '</p>';
}

/**
 * LinkedIn URL
 */
add_settings_field(
    'linkedin_url',
    __('LinkedIn URL', 'udssl'),
    'linkedin_url_callback',
    'manage-udssl',
    'linkedin_section',
    $options
);
function linkedin_url_callback($options){
    echo '<input name="udssl_options[basic][linkedin][url]" type="text"
        value="' . $options['basic']['linkedin']['url']. '" class="regular-text" >';
    echo '<p class="description" >' . __('Your LinkedIn URL', 'udssl') . '</p>';
}

/**
 * Google URL
 */
add_settings_field(
    'google_url',
    __('Google URL', 'udssl'),
    'google_url_callback',
    'manage-udssl',
    'google_section',
    $options
);
function google_url_callback($options){
    echo '<input name="udssl_options[basic][google][url]" type="text"
        value="' . $options['basic']['google']['url']. '" class="regular-text" >';
    echo '<p class="description" >' . __('Your Google URL', 'udssl') . '</p>';
}

/**
 * StackExchange URL
 */
add_settings_field(
    'stackexchange_url',
    __('StackExchange URL', 'udssl'),
    'stackexchange_url_callback',
    'manage-udssl',
    'stackexchange_section',
    $options
);
function stackexchange_url_callback($options){
    echo '<input name="udssl_options[basic][stackexchange][url]" type="text"
        value="' . $options['basic']['stackexchange']['url']. '" class="regular-text" >';
    echo '<p class="description" >' . __('Your StackExchange URL', 'udssl') . '</p>';
}

/**
 * YouTube URL
 */
add_settings_field(
    'youtube_url',
    __('YouTube URL', 'udssl'),
    'youtube_url_callback',
    'manage-udssl',
    'youtube_section',
    $options
);
function youtube_url_callback($options){
    echo '<input name="udssl_options[basic][youtube][url]" type="text"
        value="' . $options['basic']['youtube']['url']. '" class="regular-text" >';
    echo '<p class="description" >' . __('Your YouTube URL', 'udssl') . '</p>';
}

/**
 * GitHub URL
 */
add_settings_field(
    'github_url',
    __('GitHub URL', 'udssl'),
    'github_url_callback',
    'manage-udssl',
    'github_section',
    $options
);
function github_url_callback($options){
    echo '<input name="udssl_options[basic][github][url]" type="text"
        value="' . $options['basic']['github']['url']. '" class="regular-text" >';
    echo '<p class="description" >' . __('Your GitHub URL', 'udssl') . '</p>';
}

?>
