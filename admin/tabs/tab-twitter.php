<?php
/**
 * Twitter Options
 */
$options = get_option('udssl_options');
$options = $options['twitter'];

/**
 * Twitter Settings Section
 */
add_settings_section(
    'twitter_configuration',
    __('Twitter Configuration', 'udssl'),
    'twitter_configuration_callback',
    'manage-udssl');

/**
 * Twitter Section Callback
 */
function twitter_configuration_callback(){
    _e('Configure the Twitter client', 'udssl');
}

/**
 * Username
 */
add_settings_field(
    'user_name',
    __('User Name', 'udssl'),
    'user_name_callback',
    'manage-udssl',
    'twitter_configuration',
    $options
);

/**
 * User Name Callback
 */
function user_name_callback($options){
    echo '<input name="udssl_options[twitter][user_name]" type="text" class="regular-text" value="' . $options['user_name'] . '">';
}

/**
 * Consumer Key
 */
add_settings_field(
    'consumer_key',
    __('Consumer Key', 'udssl'),
    'consumer_key_callback',
    'manage-udssl',
    'twitter_configuration',
    $options
);

/**
 * Consumer Key Callback
 */
function consumer_key_callback($options){
    echo '<input name="udssl_options[twitter][consumer_key]" type="text" class="regular-text" value="' . $options['consumer_key'] . '">';
}

/**
 * Consumer Secret
 */
add_settings_field(
    'consumer_secret',
    __('Consumer Secret', 'udssl'),
    'consumer_secret_callback',
    'manage-udssl',
    'twitter_configuration',
    $options
);

/**
 * Consumer Secret Callback
 */
function consumer_secret_callback($options){
    echo '<input name="udssl_options[twitter][consumer_secret]" type="text" class="regular-text" value="' . $options['consumer_secret'] . '">';
}

/**
 * Access Token
 */
add_settings_field(
    'access_token',
    __('Access Token', 'udssl'),
    'access_token_callback',
    'manage-udssl',
    'twitter_configuration',
    $options
);

/**
 * Access Token Callback
 */
function access_token_callback($options){
    echo '<input name="udssl_options[twitter][access_token]" type="text" class="regular-text" value="' . $options['access_token'] . '">';
}

/**
 * Consumer Secret
 */
add_settings_field(
    'access_token_secret',
    __('Consumer Secret', 'udssl'),
    'access_token_secret_callback',
    'manage-udssl',
    'twitter_configuration',
    $options
);

/**
 * Consumer Secret Callback
 */
function access_token_secret_callback($options){
    echo '<input name="udssl_options[twitter][access_token_secret]" type="text" class="regular-text" value="' . $options['access_token_secret'] . '">';
    //echo ' <input name="udssl_options[reauthenticate-twitter]" type="submit" class="button-primary" value="Reauthenticate" />';
}

/**
 * No of Tweets to Diudssllay
 */
add_settings_field(
    'no_of_tweets',
    __('No of Tweets', 'udssl'),
    'no_of_tweets_callback',
    'manage-udssl',
    'twitter_configuration',
    $options
);

/**
 * No of Tweets to Diudssllay Callback
 */
function no_of_tweets_callback($options){
    echo '<input name="udssl_options[twitter][no_of_tweets]" type="text" class="small-text" value="' . $options['no_of_tweets'] . '">';
}

/**
 * No of Minutes to Keep
 */
add_settings_field(
    'time_to_expire',
    __('Mintes to Cache Tweets', 'udssl'),
    'time_to_expire_callback',
    'manage-udssl',
    'twitter_configuration',
    $options
);

/**
 * No of Minutes to Keep Callback
 */
function time_to_expire_callback($options){
    echo '<input name="udssl_options[twitter][time_to_expire]" type="text" class="small-text" value="' . $options['time_to_expire'] . '">';
}

/**
 * Save Twitter
 */
add_settings_section(
    'save_twitter_configuration',
    __('Save Configuration', 'udssl'),
    'save_callback',
    'manage-udssl');

/**
 * Save Settings Callback
 */
function save_callback($options){
    echo '<input name="udssl_options[submit-twitter]" type="submit" class="button-primary" value="Save Twitter Settings" />';
    echo ' <input name="udssl_options[reset-twitter]" type="submit" class="button-secondary" value="Reset" />';
    echo ' <input name="udssl_options[delete-twitter]" type="submit" class="button-primary" value="Delete Twitter Cache" />';
}
?>
