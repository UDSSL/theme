<?php
/**
 * UDSSL Enqueues
 */
class UDSSL_Enqueues{
    /**
     * Constructor
     */
    function __construct(){
        /**
         * WP Head Top
         */
        add_action( 'wp_head_top', array($this, 'wp_head_top') );

        /**
         * Styles
         */
        add_action( 'wp_enqueue_scripts', array($this, 'styles') );

        /**
         * Scripts
         */
        add_action( 'wp_enqueue_scripts', array($this, 'scripts') );

        /**
         * WP Head Print
         */
        add_action( 'wp_head', array($this, 'wp_head_print') );

        /**
         * WP Footer Print
         */
        add_action( 'wp_footer', array($this, 'wp_footer_print'), 99 );

        /**
         * UDSSL Title
         */
        add_filter( 'wp_title', array($this, 'udssl_wp_title'), 10, 2 );
    }

    /**
     * UDSSL Head Top
     */
    function wp_head_top(){
        $head_top = '<title>' . wp_title( '|', false, 'right' ) . '</title>';

        /**
         * Override the defaults
         */
        if(is_singular()){
            the_post();
            $id = get_the_ID();
            $seo = get_post_meta($id, 'seo', true);
            if($seo){
                $head_top = '<title>' . $seo['title'] . '</title>' . PHP_EOL;
                $head_top .= '    <meta name="description" content="' . $seo['description'] . '">' . PHP_EOL;
                if($seo['noindex']) {
                    $head_top .= '    <meta name="robots" content="noindex, nofollow">' . PHP_EOL;
                }
            }
        }

        echo $head_top;
    }

    /**
     * Enqueue Styles
     */
    function styles(){
        /**
         * Bootstrap
         */
        wp_enqueue_style( 'bootstrap', UDS_URL . 'lib/bootstrap/css/bootstrap.css' );

        /**
         * Font Awesome
         */
        wp_enqueue_style( 'font-awesome', UDS_URL . 'lib/font-awesome/css/font-awesome.css' );

        /**
         * Theme
         */
        wp_enqueue_style( 'udssl', UDS_URL . 'css/style.css' );

        /**
         * Syntax
         */
        wp_enqueue_style( 'syntax-style', UDS_URL . 'lib/syntaxhighlighter/styles/shCore.css' );
        wp_enqueue_style( 'syntax-style-theme', UDS_URL . 'lib/syntaxhighlighter/styles/shThemeDefault.css' );
    }

    /**
     * Enqueue Scripts
     */
    function scripts(){
        /**
         * Libs
         */
        wp_enqueue_script( 'uw-jquery', UDS_URL . 'lib/bootstrap/assets/jquery.js', array(), false, true );
        wp_enqueue_script( 'bootstrap', UDS_URL . 'lib/bootstrap/js/bootstrap.min.js', array('uw-jquery'), false, true );

        /**
         * Syntax
         */
        wp_enqueue_script( 'syntax-script', UDS_URL . 'lib/syntaxhighlighter/scripts/shCore.js', array('uw-jquery'), false, true );
        wp_enqueue_script( 'syntax-script-bash', UDS_URL . 'lib/syntaxhighlighter/scripts/shBrushBash.js', array('uw-jquery'), false, true );
        wp_enqueue_script( 'syntax-script-php', UDS_URL . 'lib/syntaxhighlighter/scripts/shBrushPhp.js', array('uw-jquery'), false, true );
        wp_enqueue_script( 'syntax-script-jscript', UDS_URL . 'lib/syntaxhighlighter/scripts/shBrushJScript.js', array('uw-jquery'), false, true );
    }

    /**
     * WP Head Print
     */
    function wp_head_print(){
        ?>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="<? echo UDS_URL; ?>lib/bootstrap/assets/html5shiv.js"></script>
          <script src="<? echo UDS_URL; ?>lib/bootstrap/assets/respond.min.js"></script>
        <![endif]-->

		<!-- Favicons -->
		<link rel="shortcut icon" href="<?php echo UDS_URL; ?>favicon.png">
        <link rel="apple-touch-icon" href="<?php echo UDS_URL; ?>assets/apple-touch-icon.png">
		<link rel="apple-touch-icon" sizes="72x72" href="<?php echo UDS_URL; ?>assets/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo UDS_URL; ?>assets/apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="144x144" href="<?php echo UDS_URL; ?>assets/apple-touch-icon-144x144.png">

		<!-- Facebook OpenGraph Tags
		<meta property="og:title" content="UDSSL"/>
		<meta property="og:type" content="website"/>
		<meta property="og:url" content="http://udssl.com/"/>
		<meta property="og:image" content="http://udssl.com/"/>
		<meta property="og:site_name" content="UDSSL"/>
		<meta property="fb:app_id" content=""/>
		<meta property="og:description" content="UDSSL"/>
		-->
        <?php
    }

    /**
     * WP Footer Print
     */
    function wp_footer_print(){
        ?>
		<!-- Praveen Dias -->
        <script type="text/javascript">
             SyntaxHighlighter.all()
        </script>
        <?php
    }

    /**
     * UDSSL Titlte
     */
    function udssl_wp_title($title, $sep){
        global $paged, $page;

        if ( is_feed() )
            return $title;

        $title .= get_bloginfo( 'name' );

        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) )
            $title = "$title $sep $site_description";

        if ( $paged >= 2 || $page >= 2 )
            $title = "$title $sep " . sprintf( __( 'Page %s', 'udssl' ), max( $paged, $page ) );

        return $title;
    }
}
?>
