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
            // init the FB JS SDK
            FB.init({
              appId      : '<?php echo $app_id ?>',                        // App ID from the app dashboard
              channelUrl : '//WWW.YOUR_DOMAIN.COM/channel.html', // Channel file for x-domain comms
              status     : true,                                 // Check Facebook Login status
              xfbml      : true                                  // Look for social plugins on the page
            });

            // Additional initialization code such as adding Event Listeners goes here
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
