<?php
/**
 * UDSSL Sitemap
 */
class UDSSL_Sitemap{
    /**
     * Constructor
     */
    function __construct(){
        /**
         * Sitemap Rewrite
         */
        add_action('init', array($this, 'sitemap_rewrite'));

        /**
         * Sitemap Redirect
         */
        add_action('template_redirect', array($this, 'sitemap_redirect'));
    }

    /**
     * Sitemap Rewrite
     */
    function sitemap_rewrite(){
        $sitemap = 'sitemap/?$';
        add_rewrite_rule($sitemap, 'index.php?udssl_sitemap=yes', 'top');
        add_rewrite_tag('%udssl_sitemap%', '([^&]+)');
    }

    /**
     * Sitemap Redirect
     */
    function sitemap_redirect(){
        global $wp_query;
        if(!isset($wp_query->query_vars['udssl_sitemap']))
            return;

        $query_var = $wp_query->query_vars['udssl_sitemap'];
        if($query_var == 'yes'):
            $this->output_xml_sitemap();
        endif;
        exit;
    }

    /**
     * Output XML Sitemap
     */
    function output_xml_sitemap(){
        $args = array(
            'post_type' => array('post', 'page', 'projects', 'udssl-time-tracker'),
            'posts_per_page' => -1
        );
        $the_query = new WP_Query( $args );
        $sitemap = '';

        if($the_query->have_posts()){
            while($the_query->have_posts()){
                $the_query->the_post();
                $sitemap .= '<url>' . PHP_EOL;
                $sitemap .= '<loc>' . get_permalink() . '</loc>' . PHP_EOL;
                $sitemap .= '<lastmod>' . get_the_time('Y-m-d') . '</lastmod>' . PHP_EOL;
                $sitemap .= '<changefreq>weekly</changefreq>';
                $sitemap .= '<priority>0.8</priority>';
                $sitemap .= '</url>' . PHP_EOL;
            }
        }
        header("Content-type: text/xml");
        echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        echo $sitemap;

        echo '</urlset>';
        exit;
    }
}
?>
