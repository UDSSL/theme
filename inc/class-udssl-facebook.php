<?php
/**
 * Custom Facebook Like Box Widget for UDSSL.
 */
class UDSSL_Facebook{
    function __construct() {
        add_action('wp_head', array($this, 'add_fb_script'));
        add_action('widgets_init', array($this, 'register_widget'));
    }

    function add_fb_script(){
        $udssl_options = get_option('udssl_options');
        $app_id = esc_html($udssl_options['facebook']['facebook_app_id']);
        ?>
        <div id="fb-root"></div>
        <script>
            window.fbAsyncInit = function() {
                /**
                 * Facebook Init
                 */
                FB.init({
                    appId      : '<?php echo $app_id ?>',
                    channelUrl : '<?php echo get_home_url(); ?>/channel.html',
                    status     : true, // check login status
                    cookie     : true, // enable cookies to allow the server to access the session
                    xfbml      : true  // parse XFBML
                });

                /**
                 * Subscribe to authResponseChange
                 */
                FB.Event.subscribe('auth.authResponseChange', function(response) {
                    if (response.status === 'connected') {
                      udsslAPI();
                    } else if (response.status === 'not_authorized') {
                      FB.login();
                    } else {
                      FB.login();
                    }
                });
            }

            /**
             * UDSSL Test
             *
             * Use below for testing
             * <fb:login-button show-faces="false" width="50" max-rows="1"></fb:login-button>
             */
            function udsslAPI() {
                console.log('Welcome!  Fetching your information');
                FB.api('/me', function(response) {
                  console.log('Good to see you, ' + response.name + '.');
                });
            };

            // Load the SDK asynchronously
            (function(d, s, id){
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/all.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
    <?php
    }

    function register_widget(){
        register_widget( 'UDSSL_Facebook_Widget' );
    }
}

class UDSSL_Facebook_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'udssl_fb_like_box', // Base ID
            'UDSSL Facebook Like Box', // Name
            array( 'description' => 'Adds a Facebook Like Box. Configure options in the theme settings panel' ) // Args
        );
    }

    public function form( $instance ) {
        echo '<div class="widget widget-top" style="width:100%; color:#21759B"><div class="widget-title"><h4>UDSSL Facebook Like Box</h4></div></div>';
        echo '<div class="sidebar-description">
            <p class="description" style="color:#21759B"><br />Configure Facebook Settings in Theme Settings Panel.</p>
            </div>';
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = 'UDSSL Facebook Like Box';

        return $instance;
    }

    public function widget( $args, $instance ) {
        $udssl_options = get_option('udssl_options');
        $page_url = esc_html($udssl_options['facebook']['facebook_page_id']);
        $title = esc_html($udssl_options['facebook']['facebook_widget_title']);
        $width = esc_html($udssl_options['facebook']['facebook_widget_width']);
        $height = esc_html($udssl_options['facebook']['facebook_widget_height']);

        echo '<div id="sp-fb-like-box" class="footerBox oneFourth"><div id="facebookInner">';
        echo '<h3 style="margin-bottom:10px" class="text-muted">' . $title . '</h3>';
        echo '<div class="fb-container">';
        echo '<div class="fb-like-box" data-border-color="#000" data-href="https://www.facebook.com/' . $page_url
            . '" data-width="' . $width . '" data-height="' . $height . '" data-show-faces="true" data-stream="false" data-header="false"></div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        wp_enqueue_style ('udssl_facebook_style',  UDS_URL . 'css/udssl-facebook.css');
    }
}
?>
