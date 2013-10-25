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
        /**
         * Search Form
         */
        $search = '<h3>Search UDSSL</h3>
        <form class="form-inline" role="form" id="searchForm" action="' . home_url() . '/search/" method="POST">
          <div class="form-group">
            <input type="text" class="form-control" name="search-text" id="search-text" placeholder="USB Solutions">
          </div>
          <button type="submit" class="btn btn-default">Search</button>
        </form>';
        echo $search;

        echo '<hr />';

        /**
         * Recent Posts
         */
        $args = array(
            'post_type' => array('post', 'projects'),
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

        echo '<h3>Recent Posts</h3>';
        echo $recent;


        if ( ! dynamic_sidebar('Sidebar Right') ) :
            echo '';
        endif;
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
}
?>
