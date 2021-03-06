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
     * UDSSL Store Sidebar Right
     */
    function store_right(){
        echo $this->get_search_form();
        global $wp_query;
        if($wp_query->query_vars['store_page'] == 'store'){
            echo $this->get_mini_cart();
        }
        if($wp_query->query_vars['store_page'] == 'cart'){
            echo '<hr />';
            echo '<p class="text-right">
                <a href="' . get_home_url() . '/store/" class="btn btn-info btn-lg">Visit <b>Store</b> <i class="fa fa-building fa-3x"></i></a>
                </p>';
        }
    }

    /**
     * UDSSL Mini Cart
     */
    function get_mini_cart(){
        $cart = '<hr /><h4 class="text-info text-right">UDSSL Shopping Cart <i class="fa fa-shopping-cart fa-2x"></i></h4>';
        if(!isset($_SESSION['cart']) || sizeof($_SESSION['cart']) == 0){
            $cart .= '<h4 class="text-right text-muted" ><i>Your Cart is <b>Empty</b></i></h3>';
            $cart .= '<h4 class="text-right text-info" ><i class="fa fa-hand-o-left fa-2x"></i> Add to <b>Cart</b></h4>';
        } else {
            /**
             * Table Header
             */
            $cart .= '<table id="udssl-cart" class="table table-responsive table-hover">';
            $cart .= '<thead>';
                $cart .= '<tr>';
                    $cart .= '<th>Product</th>';
                    $cart .= '<th>Quantity</th>';
                    $cart .= '<th class="text-right">Price</th>';
                $cart .= '</tr>';
            $cart .= '</thead>';
            $cart .= '<body>';

            $index = 1;
            $total = 0;
            foreach($_SESSION['cart'] as $product_name => $quantity){
                $products = get_posts('post_type=products&name=' . $product_name);
                if(!$products) wp_die('Something is wrong!');

                $product = $products[0];
                $product_meta = get_post_meta($product->ID, 'product_data', true);
                $rate = $product_meta['price'];
                $price = $rate * $quantity;
                $total += $price;

                $cart .= '<tr>';
                $cart .= '<td>' . $product_name . '</td>';
                $cart .= '<td>' . $quantity . '</td>';
                $cart .= '<td class="text-right">' . $price . '</td>';
                $cart .= '</tr>';
                $index++;
            }

                /**
                 * Total
                 */
                $cart .= '<tr>';
                $cart .= '<td></td>';
                $cart .= '<td><b>Total</b></td>';
                $cart .= '<td class="text-right"><b><span class="text-muted">USD</span> ' . $total . '</b></td>';
                $cart .= '</tr>';

                /**
                 * Checkout
                 */
                $cart .= '<tr>';
                $cart .= '<td colspan="2"></td>';
                $checkout_url = '<a href="' . home_url() . '/store/cart/" class="btn btn-default" title="View Cart">View Cart <i class="fa fa-shopping-cart"></i></a>';
                $cart .= '<td>' . $checkout_url . '</td>';
                $cart .= '</tr>';

            /**
             * Table Footer
             */
            $cart .= '</body>';
            $cart .= '</table>';
        }
        return $cart;
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
        } elseif('projects' == get_post_type() || is_page('computer-aided-control-systems')) {
            if(has_term('WLCS', 'udssl-project' ) || is_page('computer-aided-control-systems')) {
                /**
                 * WLCS Sidebar
                 */
                echo $this->get_wlcs_sidebar();
                echo $this->get_search_form();
                return true;
            }
        } elseif(has_term('Vim', 'udssl-project')){
            echo $this->vim_sidebar();
            return true;
        } elseif(has_term('Hilti', 'udssl-project')){
            echo $this->hilti_sidebar();
            return true;
        } elseif(has_term('Now Reading', 'udssl-project')){
            echo $this->now_reading_sidebar();
            return true;
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
            'posts_per_page' => 4
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
                <li class="github"><a href="' . $options["basic"]["github"]["url"] . '" target="_blank" title="GitHub"><i class="fa fa-github fa-2x text-muted"></i></a></li>
                <!-- <li class="youtube"><a href="' . $options["basic"]["youtube"]["url"] . '" target="_blank" title="YouTube"><i class="fa fa-youtube fa-2x text-muted"></i></a></li>
                <li class="stackexchange"><a href="' . $options["basic"]["stackexchange"]["url"] . '" target="_blank" title="Stackexchange"><i class="fa fa-stack-exchange fa-2x text-muted"></i></a></li> -->
                <li class="linkedin"><a href="' . $options["basic"]["linkedin"]["url"] . '" target="_blank" title="LinkedIn"><i class="fa fa-linkedin fa-2x text-muted"></i></a></li>
                <li class="googleplus"><a href="' . $options["basic"]["google"]["url"] . '" target="_blank" title="Google Plus"><i class="fa fa-google-plus fa-2x text-muted"></i></a></li>
                <li class="twitter"><a href="https://www.twitter.com/' . $options["basic"]["twitter"]["user_name"] . '" target="_blank" title="Twitter"><i class="fa fa-twitter fa-2x text-muted"></i></a></li>
                <li class="facebook"><a href="' . $options["basic"]["facebook"]["url"] . '" target="_blank" title="Facebook"><i class="fa fa-facebook fa-2x text-muted"></i></a></li>
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
                <li><p><i class="fa fa-map-marker"></i> <strong>Address:</strong> ' . $options["contact"]["data"]["address"] . '</p></li>
                <li><p><i class="fa fa-phone"></i> <strong>Phone:</strong> ' . $options["contact"]["data"]["phone"] . '</p></li>
                <li><p><i class="fa fa-envelope"></i> <strong>Email:</strong> <a href="mailto:' . $options["contact"]["data"]["email"] . '">' . $options["contact"]["data"]["email"] . '</a></p></li>
                <li><p><i class="fa fa-skype"></i> <strong>Skype:</strong> ' . $options["contact"]["data"]["skype"] . '</p></li>
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
        <a href="' . get_home_url() . '/leave/github.com/UDSSL/time-tracker" title="View Source on GitHub" class="btn "><i class="fa fa-github fa-2x"></i></a>
        <a href="' . get_home_url() . '/downloads/udssl-time-tracker/" rel="nofollow" class="btn btn-info" title="Download Now. It\'s Free!"> <span class="glyphicon glyphicon-cloud-download"></span> Download</a>';
        $plugin_pages .= ' <a href="' . get_home_url() . '/leave/wordpress.org/plugins/udssl-time-tracker/" class="btn btn-success" title="On WordPress.org"> <span class="glyphicon glyphicon-cloud-download"></span> WordPress.Org</a>
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

    /**
     * Now Reading Sidebar
     */
    function now_reading_sidebar(){
        $args = array(
            'post_type' => array('page'),
            'udssl-project' => 'Now Reading',
            'posts_per_page' => -1,
            'order' => 'ASC'
        );
        $the_query = new WP_Query( $args );
        $sidebar = '<p class="text-center" ><a id="now-reading-home-button" href="' . get_home_url() . '/udssl-now-reading/" class="btn btn-lg" title="Now Reading | Home"> <span class="glyphicon glyphicon-home"></span></a></p>';
        $sidebar .= '<p class="text-center" ><img src="' . get_home_url() . '/assets/udssl-now-reading/now-reading-icon.png" class="img-thumbnail img-rounded"/></p>';
        $sidebar .= '<h3 class="text-primary text-center">Now Reading</h3>';
        $sidebar .= '<p class="text-muted text-center">Premium WordPress Plugin</p>';
        $sidebar .= '<div class="bs-example" style="padding-bottom: 24px; text-align:center;">
        <a href="' . get_home_url() . '/downloads/udssl-now-reading/" rel="nofollow"  class="btn btn-info" title="View On GitHub"> <span class="glyphicon glyphicon-github"></span> View On GitHub</a>
        </div>';
        $sidebar .= '<ul class="list-group">';

        if($the_query->have_posts()){
            while($the_query->have_posts()){
                $the_query->the_post();
                $sidebar .= '<li class="list-group-item">';
                $sidebar .= '<a href="' . get_permalink() . '" ><h4>';
                $sidebar .= get_the_title() . '</h4></a>';

                $id = get_the_ID();
                $seo = get_post_meta($id, 'seo', true);
                if($seo){
                    $sidebar .= '<p>' . $seo['description'] . '<br /><span class="label label-info">';
                }

                $sidebar .= 'NR</span> ' .
                    '<a class="read-more" href="' . get_permalink() . '" title="' . get_the_title() . '" ><span class="label label-success">Read More</span></a></p>' . PHP_EOL;
                $sidebar .= '</li>';
            }
        }
        $sidebar .= '</ul>';
        return $sidebar;
    }

    /**
     * Vim Sidebar
     */
    function vim_sidebar(){
        $args = array(
            'post_type' => array('page'),
            'udssl-project' => 'Vim',
            'posts_per_page' => -1,
            'order' => 'ASC'
        );
        $the_query = new WP_Query( $args );
        $vim = '<p class="text-center" ><img alt="Vim Editor Logo" title="Vim Editor Logo" src="' . get_home_url() . '/assets/vim/vim-logo.png" class="img-centered img-responsive"/></p>';
        $vim .= '<ul class="list-group">';

        if($the_query->have_posts()){
            while($the_query->have_posts()){
                $the_query->the_post();
                $vim .= '<li class="list-group-item">';
                $vim .= '<a href="' . get_permalink() . '" ><h4>';
                $vim .= get_the_title() . '</h4></a>';

                $id = get_the_ID();
                $seo = get_post_meta($id, 'seo', true);
                if($seo){
                    $vim .= '<p>' . $seo['description'] . '<br /><span class="label label-info">';
                }

                $vim .= 'Vim</span> ' .
                    '<a class="read-more" href="' . get_permalink() . '" title="' . get_the_title() . '" ><span class="label label-success">Read More</span></a></p>' . PHP_EOL;
                $vim .= '</li>';
            }
        }
        $vim .= '</ul>';
        return $vim;
    }

    /**
     * Hilti Sidebar
     */
    function hilti_sidebar(){
        $args = array(
            'post_type' => array('page'),
            'udssl-project' => 'Hilti',
            'posts_per_page' => -1,
            'order' => 'ASC'
        );
        $the_query = new WP_Query( $args );
        $hilti = '<p class="text-center" ><img alt="Hilti Sri Lanka" title="Hilti Sri Lanka" src="' . get_home_url() . '/assets/hilti/hilti-sri-lanka.png" class="img-centered img-responsive"/></p>
            <h4 class="text-muted text-right">Hilti. Outperform. Outlast.</h4><p class="text-right" ><a href="https://www.facebook.com/HiltiGroup" target="_blank" title="Hilti Facebook"><i class="fa fa-facebook fa-4x" style="color:red"></i></a>  <a href="https://www.youtube.com/user/HiltiGroup" target="_blank" title="Hilti YouTube"><i class="fa fa-youtube fa-4x" style="color:red"></i></a>
            </p>';
        $hilti .= '<ul class="list-group">';

        if($the_query->have_posts()){
            while($the_query->have_posts()){
                $the_query->the_post();
                $hilti .= '<li class="list-group-item">';
                $hilti .= '<a href="' . get_permalink() . '" ><h4>';
                $hilti .= get_the_title() . '</h4></a>';

                $id = get_the_ID();
                $seo = get_post_meta($id, 'seo', true);
                if($seo){
                    $hilti .= '<p>' . $seo['description'] . '<br /><span class="label label-info">';
                }

                $hilti .= 'Hilti</span> ' .
                    '<a class="read-more" href="' . get_permalink() . '" title="' . get_the_title() . '" ><span class="label label-success">Read More</span></a></p>' . PHP_EOL;
                $hilti .= '</li>';
            }
        }
        $hilti .= '</ul>';
        $hilti .= '<p><img alt="Hilti Sri Lanka" title="Hilti Sri Lanka" src="' . get_home_url() . '/assets/hilti/hilti-toolbag.jpg" class="img-centered img-responsive"/></p>';

        return $hilti;
    }
}
?>
