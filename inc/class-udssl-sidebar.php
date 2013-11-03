<?php
/**
 * Sidebars
 */
class UDSSL_Sidebar{
    /**
     * Constructor
     */
    function __construct(){
        add_action('after_setup_theme', array($this, 'register_sidebars'));
    }

    /**
     * Register Theme Sidebars
     */
    function register_sidebars(){
        /**
         * Footer Left
         */
        $args = array(
            'name' => __('Footer Left', 'udssl'),
            'id'   => 'footer-left'
        );
        register_sidebar($args);

        /**
         * Footer Middle
         */
        $args = array(
            'name' => __('Footer Middle', 'udssl'),
            'id'   => 'footer-middle'
        );
        register_sidebar($args);

        /**
         * Footer Right
         */
        $args = array(
            'name' => __('Footer Right', 'udssl'),
            'id'   => 'footer-right'
        );
        register_sidebar($args);

        /**
         * Sidebar Right
         */
        $args = array(
            'name' => __('Sidebar Right', 'udssl'),
            'id'   => 'sidebar-right'
        );
        register_sidebar($args);
    }

    /**
     * UDSSL Single Sidebar Left
     */
    function single_left() {
    }

    /**
     * UDSSL Single Sidebar Right
     */
    function single_right() {
        if('udssl-time-tracker' == get_post_type() ||  is_page('udssl-time-tracker')){
            /**
             * Plugin Sidebar
             */
            echo $this->get_plugin_sidebar();
            return true;
        } elseif(('projects' == get_post_type())) {
            if(has_term('WLCS', 'udssl-project' ) ) {
                /**
                 * WLCS Sidebar
                 */
                echo $this->get_wlcs_sidebar();
                echo $this->get_search_form();
                return true;
            }
        }

        /**
         * Search Form
         */
        echo $this->get_search_form();
        echo '<hr />';

        /**
         * UDSSL Slider
         */
        echo $this->get_slider();

        /**
         * Recent Posts
         */
        echo $this->get_recent_posts();
    }

    /**
     * Recent Posts
     */
    function get_recent_posts(){
        $args = array(
            'post_type' => array('post'),
            'posts_per_page' => 10
        );
        $the_query = new WP_Query( $args );
        $recent = '<ul class="list-group">';

        if($the_query->have_posts()){
            while($the_query->have_posts()){
                $the_query->the_post();
                $recent .= '<li class="list-group-item">';
                $recent .= '<a href="' . get_permalink() . '" ><h4>';
                $recent .= get_the_title() . '</h4></a>';

                $id = get_the_ID();
                $seo = get_post_meta($id, 'seo', true);
                if($seo){
                    $recent .= '<p>' . $seo['description'] . '<br /><span class="label label-info">';
                }

                $recent .= get_the_time('Y-m-d') . '</span> ' .
                    '<a class="read-more" href="' . get_permalink() . '" title="' . get_the_title() . '" ><span class="label label-success">Read More</span></a></p>' . PHP_EOL;
                $recent .= '</li>';
            }
        }
        $recent .= '</ul>';
        return $recent;
    }

    /**
     * Search Form
     */
    function get_search_form(){
        $search = '<h4 class="text-right text-muted" >Search UDSSL</h4>
        <form class="form-inline text-right" role="form" id="searchForm" action="' . home_url() . '/search/" method="GET">
          <div class="form-group">
            <input type="text" class="form-control" name="q" id="search-text" placeholder="USB Solutions">
          </div>
          <button type="submit" class="btn btn-default">Search</button>
        </form>';
        return $search;
    }

    /**
     * UDSSL Home Page Sidebar
     */
    function home_sidebar() {
        echo '<img src="' . UDS_URL . 'assets/udssl-logo.png" class="img-responsive" alt="UDSSL Logo">';
    }

    /**
     * UDSSL Social Icons
     */
    function social_icons(){
        $options = get_option('udssl_options');
        return '<div class="social-icons">
            <ul class="social-icons">
                <li class="github"><a href="' . $options["basic"]["github"]["url"] . '" target="_blank" title="GitHub"><i class="icon-github icon-2x"></i></a></li>
                <li class="youtube"><a href="' . $options["basic"]["youtube"]["url"] . '" target="_blank" title="YouTube"><i class="icon-youtube icon-2x"></i></a></li>
                <li class="stackexchange"><a href="' . $options["basic"]["stackexchange"]["url"] . '" target="_blank" title="Stackexchange"><i class="icon-stackexchange icon-2x"></i></a></li>
                <li class="linkedin"><a href="' . $options["basic"]["linkedin"]["url"] . '" target="_blank" title="LinkedIn"><i class="icon-linkedin icon-2x"></i></a></li>
                <li class="googleplus"><a href="' . $options["basic"]["google"]["url"] . '" target="_blank" title="Google Plus"><i class="icon-google-plus icon-2x"></i></a></li>
                <li class="twitter"><a href="https://www.twitter.com/' . $options["basic"]["twitter"]["user_name"] . '" target="_blank" title="Twitter"><i class="icon-twitter icon-2x"></i></a></li>
                <li class="facebook"><a href="' . $options["basic"]["facebook"]["url"] . '" target="_blank" title="Facebook"><i class="icon-facebook icon-2x"></i></a></li>
            </ul>
        </div>';
    }

    /**
     * UDSSL Contact Options
     */
    function contact_options(){
        $options = get_option('udssl_options');
        $contact = '<div class="clearfix"></div>
        <div class="contact-details">
            <ul class="contact">
                <li><p><i class="icon-map-marker"></i> <strong>Address:</strong> ' . $options["contact"]["data"]["address"] . '</p></li>
                <li><p><i class="icon-phone"></i> <strong>Phone:</strong> ' . $options["contact"]["data"]["phone"] . '</p></li>
                <li><p><i class="icon-envelope"></i> <strong>Email:</strong> <a href="mailto:' . $options["contact"]["data"]["email"] . '">' . $options["contact"]["data"]["email"] . '</a></p></li>
                <li><p><i class="icon-skype"></i> <strong>Skype:</strong> ' . $options["contact"]["data"]["skype"] . '</p></li>
            </ul>
        </div>';
        return $contact;
    }

    /**
     * Time Tracker Sidebar
     */
    function get_plugin_sidebar(){
        $args = array(
            'post_type' => array('udssl-time-tracker'),
            'posts_per_page' => -1,
            'order' => 'ASC'
        );
        $the_query = new WP_Query( $args );
        if(is_page('udssl-time-tracker')){
            $plugin_pages = '';
        } else {
            $plugin_pages = '<h2 class="text-primary text-right"><a title="Home of UDSSL Time Tracker" id="time-tracker-home" href="' . get_home_url()
                . '/udssl-time-tracker/" >UDSSL Time Tracker <span class="glyphicon glyphicon-home"></span></a></h2>';
            $plugin_pages .= '<p class="text-muted text-right">The WordPress Time Tracker Plugin</p>';
        }
        $plugin_pages .= '<div style="padding-bottom: 24px; text-align:right;">
        <a href="https://github.com/UDSSL/time-tracker" target="_blank" title="View Source on GitHub" class="btn "><i class="icon-github icon-2x"></i></a>
        <a href="' . get_home_url() . '/downloads/udssl-time-tracker/" class="btn btn-info" title="Download Now. It\'s Free!"> <span class="glyphicon glyphicon-cloud-download"></span> Download</a>';
        $plugin_pages .= ' <a href="http://wordpress.org/plugins/udssl-time-tracker/" class="btn btn-success" title="On WordPress.org"> <span class="glyphicon glyphicon-cloud-download"></span> WordPress.Org</a>
        </div>';
        $plugin_pages .= '<ul class="list-group">';

        if($the_query->have_posts()){
            while($the_query->have_posts()){
                $the_query->the_post();
                $plugin_pages .= '<li class="list-group-item">';
                $plugin_pages .= '<a href="' . get_permalink() . '" ><h4>';
                $plugin_pages .= get_the_title() . '</h4></a>';

                $id = get_the_ID();
                $seo = get_post_meta($id, 'seo', true);
                if($seo){
                    $plugin_pages .= '<p>' . $seo['description'] . '<br /><span class="label label-info">';
                }

                $plugin_pages .= 'Time Tracker</span> ' .
                    '<a class="read-more" href="' . get_permalink() . '" title="' . get_the_title() . '" ><span class="label label-success">Read More</span></a></p>' . PHP_EOL;
                $plugin_pages .= '</li>';
            }
        }
        $plugin_pages .= '</ul>';
        return $plugin_pages;
    }

    /**
     * WLCS Sidebar
     */
    function get_wlcs_sidebar(){
        $args = array(
            'post_type' => array('projects'),
            'udssl-projects' => 'WLCS',
            'posts_per_page' => -1,
            'order' => 'ASC'
        );
        $the_query = new WP_Query( $args );
        $wlcs_pages = '<h3 class="text-primary text-right">Water Level Control System</h3>';
        $wlcs_pages .= '<p class="text-muted text-right">Computer Aided Control with LabVIEW</p>';
        $wlcs_pages .= '<div class="bs-example" style="padding-bottom: 24px; text-align:right;">
        <a href="' . get_home_url() . '/computer-aided-control-systems/" class="btn btn-info" title="Computer Aided Control Project | Water Level Control"> <span class="glyphicon glyphicon-cog"></span> Control Engineering</a>
        </div>';
        $wlcs_pages .= '<ul class="list-group">';

        if($the_query->have_posts()){
            while($the_query->have_posts()){
                $the_query->the_post();
                $wlcs_pages .= '<li class="list-group-item">';
                $wlcs_pages .= '<a href="' . get_permalink() . '" ><h4>';
                $wlcs_pages .= get_the_title() . '</h4></a>';

                $id = get_the_ID();
                $seo = get_post_meta($id, 'seo', true);
                if($seo){
                    $wlcs_pages .= '<p>' . $seo['description'] . '<br /><span class="label label-info">';
                }

                $wlcs_pages .= 'WLCS</span> ' .
                    '<a class="read-more" href="' . get_permalink() . '" title="' . get_the_title() . '" ><span class="label label-success">Read More</span></a></p>' . PHP_EOL;
                $wlcs_pages .= '</li>';
            }
        }
        $wlcs_pages .= '</ul>';
        return $wlcs_pages;
    }

    /**
     * UDSSL Slider
     */
    function get_slider(){
        $slider = '
            <!-- http://www.basic-slider.com/ -->
            <div class="row" ><div class="col-md-12">
            <div id="udssl-slideshow">
                <img id="featured-slider" src="' . get_home_url() . '/assets/slider/featured.png" />
                <ul class="bjqs">
                    <li><a href="' . get_home_url() . '/udssl-time-tracker/" ><img title="UDSSL Time Tracker" src="'
                    . get_home_url() . '/assets/slider/udssl-time-tracker-english.png" /></a></li>
                    <li><a href="' . get_home_url() . '/udssl-time-tracker/" ><img title="Track Your Time Easily" src="'
                    . get_home_url() . '/assets/slider/udssl-time-tracker-sinhala.png" /></a></li>
                    <li><a href="' . get_home_url() . '/udssl-time-tracker/" ><img title="Visualize Your Time Data" src="'
                    . get_home_url() . '/assets/slider/udssl-time-tracker-tamil.png" /></a></li>
                    </ul>
            </div>
            <hr />
            </div>
            </div>
            <script>
                jQuery(document).ready(function($) {
                    $("#udssl-slideshow").bjqs({
                        "height" : 360,
                        "width" : 360,
                        "showcontrols" : false,
                        "responsive" : true
                    });
                });
            </script>
            ';
        return $slider;
    }
}
?>
