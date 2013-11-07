<?php
/**
 * UDSSL WordPress Theme Components
 */
class UDSSL_WPTheme{
    /**
     * Replace Array
     */
    private $replace_array;

    /**
     * Constructor
     */
    function __construct(){
        $this->replace_array = array(
            'assets' => get_home_url(). '/assets/',
            'theme' => get_home_url(). '/udssl-theme/',
            'plugin' => get_home_url(). '/udssl-time-tracker/',
            'root' => get_home_url(). '/'
        );
        add_filter('the_content', array($this, 'the_content'));
    }

    /**
     * Content Filter
     */
    function the_content($content){
        $content = preg_replace_callback('!\{\{(\w+)\}\}!', array($this, 'replace_value'), $content);
        return $content;
    }

    /**
     * Replace stubs
     */
    function replace_value($matches){
        if( $this->replace_array ):
            return $this->replace_array[trim($matches[1])];
        else:
            return false;
        endif;
    }

    /**
     * UDSSL Entry Meta Header
     */
    function entry_meta_header() {
    }

    /**
     * UDSSL Entry Meta Footer
     */
    function entry_meta_footer() {
        $meta = '<hr /><div class="entry-meta text-right">
            <span class="date">
                <a href="' . esc_url( get_permalink() ) . '" title="' . esc_attr( sprintf( __( 'Permalink to %s', 'udssl' ), the_title_attribute( 'echo=0' ) ) ) . '" rel="bookmark">
                    <i><time class="entry-date text-muted" datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time></i>
                </a> </span> <span class="author vcard"><span class="text-muted"> </span><a
                class="url fn n text-muted" href="https://plus.google.com/u/0/102763545776466339141" title="View Google Profile of Praveen Dias" rel="author"><i>Praveen Dias</i></a>
            </span>
        </div><hr />';
        echo $meta;
    }
}
?>
