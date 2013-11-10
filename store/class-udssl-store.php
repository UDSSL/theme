<?php
/**
 * UDSSL Store
 */
class UDSSL_Store{
    /**
     * UDSSL Store PayPal
     */
    public $paypal;

    /**
     * Database
     */
    public $database;

    /**
     * Store Admin
     */
    public $admin;

    /**
     * Shopping Cart
     */
    public $cart;

    /**
     * Store Instance Constructor
     */
    function __construct(){
        require_once UDS_PATH . 'store/processors/paypal/class-udssl-paypal.php';
        $this->paypal = new UDSSL_PayPal_Client();

        require_once UDS_PATH . 'store/class-udssl-store-database.php';
        $this->database = new UDSSL_Store_Database();

        require_once UDS_PATH . 'store/admin/class-udssl-store-admin.php';
        $this->admin= new UDSSL_Store_Admin();

        require_once UDS_PATH . 'store/class-udssl-store-cart.php';
        $this->cart = new UDSSL_Store_Cart();

        /**
         * Register Products Post Type
         */
        add_action('init', array($this, 'register_products_post_type'));

        /**
         * Add Meta Boxes
         */
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));

        /**
         * Save Meta Boxes
         */
        add_action('save_post', array($this, 'save_meta_boxes'));


    }

    /**
     * UDSSL Store Installation
     */
    function install(){
        $this->register_products_post_type();
        $this->database->create_sales_table();
        $this->store->store_rewrite();
    }

    /**
     * Register Products Post Type
     */
    function register_products_post_type(){
        $labels = array(
            'name'          => __('Products', 'udssl'),
            'singular_name' => __('Product', 'udssl'),
            'add_new'       => __('Add Product', 'udssl'),
            'view_item'     => __('View Product', 'udssl'),
            'add_new_item'  => __('Add New Product', 'udssl'),
            'edit_item'     => __('Edit Product', 'udssl')
        );
        $args = array(
            'labels'        => $labels,
            'public'        => true,
            'supports'      => array('title', 'editor', 'thumbnail'),
            'menu_icon'     => UDS_URL . 'favicon.png'
        );
        register_post_type('products', $args);
    }

    /**
     * Add Meta Boxes
     */
    function add_meta_boxes(){
		add_meta_box(
			'price_box',
			__('Price', 'udssl'),
			array($this, 'price_box_cb'),
			'products',
			'side',
            'default'
        );

		add_meta_box(
			'info_box',
			__('Info', 'udssl'),
			array($this, 'info_box_cb'),
			'products',
			'side',
            'default'
        );
    }

    /**
     * Price Box Callback
     */
    function price_box_cb($post){
        wp_nonce_field('udssl_price_box', 'udssl_price_box_nonce');
        $product_data = get_post_meta( $post->ID, 'product_data', true );
        if('' == $product_data){
            $product_data['price'] = 10;
        }
        echo '<label for="product_price">';
           _e( "Product Price", 'udssl' );
        echo '</label> ';
        echo '<input type="text" id="product_price" name="product_price" value="' . esc_attr($product_data['price']) . '" size="25" />';
    }

    /**
     * Info Box Callback
     */
    function info_box_cb($post){
        $product_data = get_post_meta( $post->ID, 'product_data', true );
        if('' == $product_data){
            $product_data['filename'] = 'Sample_File.pdf';
            $product_data['downloads'] = 0;
        }
        echo '<label for="product_name">';
           _e( "Product File", 'udssl' );
        echo '</label> ';
        echo '<input type="text" id="product_file" name="product_file" value="' . esc_attr($product_data['filename']) . '" size="25" />';

        echo '<br /><label for="product_downloads">';
           _e( "No of Downloads", 'udssl' );
        echo '</label> ';
        echo '<input type="text" id="product_downloads" name="product_downloads" value="' . esc_attr($product_data['downloads']) . '" size="25" />';
    }

    /**
     * Save Meta Boxes
     */
    function save_meta_boxes($post_id){
        if(! isset( $_POST['udssl_price_box_nonce']))
            return $post_id;

        $nonce = $_POST['udssl_price_box_nonce'];

        if(!wp_verify_nonce($nonce,'udssl_price_box'))
          return $post_id;

        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
          return $post_id;

        if('products' == $_POST['post_type']){
            if(!current_user_can('edit_page', $post_id))
                return $post_id;
        }

        $product_data = get_post_meta($post->ID, 'product_data', true);
        $product_data['price'] = sanitize_text_field($_POST['product_price']);
        $product_data['filename'] = sanitize_text_field($_POST['product_file']);
        $product_data['downloads'] = (int) $_POST['product_downloads'];

        update_post_meta($post_id, 'product_data', $product_data);
    }

    /**
     * Update Purchase Counter
     */
    function update_purchases($item_id, $quantity){
        $product_data = get_post_meta($item_id, 'product_data', true);
        $product_data['downloads'] += $quantity;
        update_post_meta($item_id, 'product_data', $product_data);
    }
}
?>
