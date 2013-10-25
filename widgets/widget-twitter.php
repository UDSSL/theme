<?php
/**
 * UDSSL Twitter Widget
 */
class UDSSL_Twitter_Widget extends WP_Widget {
    /**
     * Construct the widget
     */
    public function __construct() {
        parent::__construct(
            'udssl_twitter_widget',
            'UDSSL Twitter Widget',
            array( 'description' => 'The Twitter Widget of UDSSL' )
        );
    }

    /**
     * Backend configuration form
     */
    public function form($instance) {
        echo '<div class="widget widget-top" style="width:100%; color:#21759B"><div class="widget-title"><h4>UDSSL Twitter Widget</h4></div></div>';
        echo '<div class="sidebar-description">
            <p class="description" style="color:#21759B"><br />Configure Twitter Settings in Theme Settings Panel.</p>
            </div>';
    }

    /**
     * Front end of the Twitter widget
     */
    public function widget( $args, $instance ) {
        require_once UDS_PATH . 'inc/class-udssl-twitter.php';
        $udssl_twitter = new UDSSL_Twitter('udssl_options');
        $udssl_twitter_html = $udssl_twitter->get_widget_html();

        echo '<div id="spacer-widget" class="footerBox oneFourth">';
        echo $udssl_twitter_html;
        echo '</div>';
    }
}

/**
 * UDSSL Twitter Class
 */
class WP_Twitter{
    /**
     * UDSSL Twitter Widget Constructor
     */
    function __construct() {
        add_action('widgets_init', array($this, 'register_widget'));
    }

    /**
     * Register Twitter Widget
     */
    function register_widget(){
        register_widget( 'UDSSL_Twitter_Widget' );
    }
}
?>
