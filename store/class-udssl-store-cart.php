<?php
/**
 * UDSSL Store Cart
 */
class UDSSL_Store_Cart{
    /**
     * Constructor
     */
    function __construct(){
        /**
         * UDSSL Store Rewrite
         */
        add_action('init', array($this, 'store_rewrite'));

        /**
         * UDSSL Store Redirect
         */
        add_action('template_redirect', array($this, 'store_redirect'));

        /**
         * Cart Operations
         */
        add_action('init', array($this, 'cart_operations'));
    }

    /**
     * UDSSL Store Rewrite
     */
    function store_rewrite(){
        $store = 'store/([^/]*)/?$';
        add_rewrite_rule($store, 'index.php?store_page=$matches[1]', 'top');

        $store = 'store/?$';
        add_rewrite_rule($store,   'index.php?store_page=store', 'top');

        add_rewrite_tag('%store_page%', '([^&]+)');
    }

    /**
     * UDSSL Store Redirect
     */
    function store_redirect(){
        global $wp_query;
        if(!isset($wp_query->query_vars['store_page']))
            return;

        if(!isset($_SESSION['cart'])){
            wp_die('Cart not defined. Please enable cookies.');
        }

        $query_var = $wp_query->query_vars['store_page'];

        if($query_var == 'store'){
            $this->store();
        } else if($query_var == 'cart'){
            $this->cart();
        } else if($query_var == 'checkout'){
            $this->checkout();
        } else if($query_var == 'success'){
            $this->success();
        } else if($query_var == 'error'){
            $this->error();
        } else if($query_var == 'download'){
            $this->download();
        } else {
            wp_die('Unsupported Store Request.');
        }
        exit;
    }

    /**
     * UDSSL Store
     */
    function store(){
        require_once UDS_PATH . 'store/templates/template-store.php';
        exit;
    }

    /**
     * UDSSL Shopping Cart
     */
    function cart(){
        require_once UDS_PATH . 'store/templates/template-cart.php';
        exit;
    }

    /**
     * UDSSL PayPal Checkout
     */
    function checkout(){

        if(!isset($_SESSION['cart']) || sizeof($_SESSION['cart']) == 0){
            wp_die('Cart is Empty');
        } else {
            $cart = array();
            $total = 0;
            foreach($_SESSION['cart'] as $product_name => $quantity){
                $products = get_posts('post_type=products&name=' . $product_name);
                if(!$products) wp_die('Something is wrong!');

                $product = $products[0];
                $product_meta = get_post_meta($product->ID, 'product_data', true);
                $rate = $product_meta['price'];
                $price = $rate * $quantity;
                $total += $price;

                /**
                 * Fill PayPal Cart
                 */
                $cart[] = array(
                    'id' => $product->ID,
                    'transaction_id' => 'udssl_' . $product->ID,
                    'name' => $product_name,
                    'rate' => $rate,
                    'quantity' => $quantity,
                    'amount' => $price
                );
            }

            global $udssl_theme;
            $udssl_theme->store->paypal->setCheckout($cart, $total);
        }
        exit;
    }

    /**
     * Checkout Completed - Download
     */
    function download(){
        /**
         * Sanitize inputs
         */
        $token = isset($_GET['token'])? sanitize_text_field($_GET['token']): '';
        $payer_id = isset($_GET['PayerID'])? sanitize_text_field($_GET['PayerID']): '';

        /**
         * Validate Inputs
         */
        if('' == $token || '' == $payer_id){
            wp_die('Invalid transaction');
        }

        /**
         * Download Item
         */
        global $udssl_theme;
        $exist = $udssl_theme->store->database->check_token($token);
        if(!$exist){
            wp_die('Invalid Token');
        }

        $getEC = $udssl_theme->store->paypal->getCheckoutDetails($payer_id, $token);
        if(!$getEC && isset($_SESSION) && isset($_SESSION['getEC'])){
            $getEC = $_SESSION['getEC'];
            $reload = true;
        } else {
            $_SESSION['downloads_url'] = get_home_url() . '/store/download/?token=' . $token . '&PayerID=' . $payer_id;
            $_SESSION['cart'] = array();
            $reload = false;
        }

        if($getEC){
            $_SESSION['getEC'] = $getEC;
            $downloads = array();
            foreach($getEC->PaymentDetails[0]->PaymentDetailsItem as $paid_item){
                $download_link = 'udssl_dd_' . $payer_id . '_' . rand(1000, 9999) . '_' . current_time('timestamp');
                $item = array(
                    'name' => $paid_item->Name,
                    'number' => $paid_item->Number,
                    'quantity' => $paid_item->Quantity,
                    'rate' => $paid_item->Amount->value,
                    'link' => $download_link
                );
                list($udssl, $item_id) = explode('_', $paid_item->Number);
                $downloads[] =  $item;
                if(!$reload){
                    $udssl_theme->store->database->checkout_complete($getEC, $item_id, $download_link);
                    $udssl_theme->store->update_purchases($item_id, $paid_item->Quantity);
                }
            }

            if(!$reload){
                $udssl_theme->store->paypal->doCheckout($payer_id, $token, $getEC->PaymentDetails[0]->OrderTotal->value);
                $udssl_theme->store->database->send_email($getEC, $downloads );
            }

            include UDS_PATH . 'store/templates/template-download.php';
            exit;
        }
        wp_die('UDSSL: PayPal Payment Error');
    }

    /**
     * UDSSL Cart Operations
     */
    function cart_operations(){
        if(!isset($_POST['cart_action'])) return false;

        $cart_action = sanitize_text_field($_POST['cart_action']);
        $item_name = sanitize_text_field($_POST['item_name']);
        if('' == $cart_action || '' == $item_name) return false;

        if('add_one' == $cart_action){
            if(isset($_SESSION['cart'][$item_name])){
                $_SESSION['cart'][$item_name] += 1;
            } else {
                $_SESSION['cart'][$item_name] = 1;
            }
        }

        if('remove_all' == $cart_action){
            if(isset($_SESSION['cart'][$item_name])){
                unset($_SESSION['cart'][$item_name]);
            }
        }
    }
}
?>
