<?php
/**
 * Twitter for UDSSL
 */
class UDSSL_Twitter{
    /**
     * Twitter Application Credentials
     */
    private $consumer_key, $consumer_secret;

    /**
     * Twitter User Credentials
     */
    private $access_token, $access_token_secret;

    /**
     * Twitter Configuration
     */
    private $data;

    /**
     * Construct the Twitter Client
     */
    function __construct(){
        /**
         * Get UDSSL Options
         */
        $options = get_option('udssl_options');
        $options = $options['twitter'];

        /**
         * Provide Application Credentials
         */
        $this->consumer_key = trim($options['consumer_key']);
        $this->consumer_secret = trim($options['consumer_secret']);

        /**
         * Provide User Credentials
         */
        $this->access_token = trim($options['access_token']);
        $this->access_token_secret = trim($options['access_token_secret']);

        $this->data = array(
            'count' => $options['no_of_tweets'],
            'data_expiration' => MINUTE_IN_SECONDS * $options['time_to_expire'],
            'username' => $options['user_name']
        );
    }

    /**
     * Get The Html
     */
    function get_widget_html(){
        $output = '<h3 class="text-muted" >Latest Tweets</h3>';
        $tweets = $this->get_tweets();
        $output .= '<ul id="udssl-tweets">';

        foreach($tweets as $tweet){
            $output .= '<li class="udssl-single-tweet">';
            $tweet = '<span class="glyphicon glyphicon-share-alt"></span> ' . $tweet['text'];
            $reg_tcos = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
            if(preg_match($reg_tcos, $tweet, $url)){
                $replacement = '<a href="' . $url[0] . '">' . $url[0] . '</a> ';
                $output .= preg_replace($reg_tcos, $replacement, $tweet);
            } else {
                $output .= $tweet;
            }
            $output .= '</li>';
        }
        $output .= '</ul>';

        /**
         * Twitter Follow Button
         */
        $output .= '<a href="https://twitter.com/' . $this->data['username'] . '" class="twitter-follow-button" data-show-count="false" data-lang="en">Follow @udssl</a>';
        $output .= '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
        return $output;
    }

    /**
     * Get the Tweet List
     */
    function get_tweets(){
        $tweets = $this->get_user_timeline();
        foreach($tweets as $tweet){
            if(isset($tweet->entities->urls[0])){
                $url = $tweet->entities->urls[0]->expanded_url;
            } else {
                $url = '';
            }
            $output[] = array(
                'text' => $tweet->text,
                'created_at' => $tweet->created_at,
                'url' => $url
            );
        }
        return $output;
    }

    /**
     * Get the User Timeline
     */
    function get_user_timeline(){
        //delete_transient('twitter_user_timeline');
        $user_timeline = get_transient('twitter_user_timeline');
        if(false == $user_timeline){
            $user_timeline = $this->get_user_timeline_twitter();
            set_transient('twitter_user_timeline', $user_timeline, $this->data['data_expiration']);
        }
        return $user_timeline;
    }

    /**
     * Get the User Timeline from Twitter
     */
    function get_user_timeline_twitter(){
        $count = $this->data['count'];

        $nonce = md5(wp_create_nonce('twitter_user_timeline') . time());

        $timestamp = time();

        $http_method = 'GET';

        $base_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $url = $base_url . '?count=' . $count;

        $parameter_string  = 'count=' . $count;
        $parameter_string .= '&oauth_consumer_key=' . $this->consumer_key;
        $parameter_string .= '&oauth_nonce=' . $nonce;
        $parameter_string .= '&oauth_signature_method=HMAC-SHA1';
        $parameter_string .= '&oauth_timestamp=' . $timestamp;
        $parameter_string .= '&oauth_token=' . $this->access_token;
        $parameter_string .= '&oauth_version=1.0';

        $base_string = $http_method . '&' . rawurlencode($base_url) . '&' . rawurlencode($parameter_string);

        $signing_key = $this->consumer_secret . '&' . $this->access_token_secret;

        $signature = hash_hmac('sha1', $base_string, $signing_key, true);
        $signature = base64_encode($signature);
        $signature = rawurlencode($signature);

        /**
         * Authorization Header
         */
        $authorization = '
            OAuth oauth_consumer_key="' . $this->consumer_key . '",
              oauth_nonce="' . $nonce . '",
              oauth_signature="' . $signature . '",
              oauth_signature_method="HMAC-SHA1",
              oauth_timestamp="' . $timestamp . '",
              oauth_token="' . $this->access_token . '",
              oauth_version="1.0"';

        $args = array(
            'method' => 'GET',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.1',
            'blocking' => true,
            'sslverify' => true,
            'headers' => array(
                'Content-type' => 'application/x-www-form-urlencoded;charset=UTF-8',
                'Authorization' => $authorization,
                'Accept' => 'application/json',
                'Accept-Language' => 'en_US'
            ),
            'body' => '',
            'cookies' => array()
        );

        $response = wp_remote_post($url, $args);

        if ( is_wp_error( $response ) ) {
           $error_message = $response->get_error_message();
           wp_die("Something went wrong: $error_message");
        } else {
           $body = wp_remote_retrieve_body($response);
           $data = json_decode($body);
           return $data;
        }
    }
}
?>
