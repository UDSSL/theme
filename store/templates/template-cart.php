<?php
/**
 * UDSSL Cart Template
 */
get_header();
global $udssl_theme;
?>
<div class="row">
    <div class="col-md-8">
        <?php
        $cart = '<h1 class="text-info text-right">UDSSL Shopping Cart <i class="fa fa-shopping-cart fa-2x"></i></h1>';
        if(!isset($_SESSION['cart']) || sizeof($_SESSION['cart']) == 0){
            $cart .= '<h3 class="text-right text-muted" ><i>Your Cart is <b>Empty</b></i></h3>';
            $cart .= '<h4 class="text-right text-info" >Visit <b>Store</b> <i class="fa fa-hand-o-right fa-2x"></i></h4>';
        } else {
            $cart .= '<hr />';

            /**
             * Table Header
             */
            $cart .= '<div class="table-responsive" ><table id="udssl-cart" class="table table-hover">';
            $cart .= '<thead>';
                $cart .= '<tr>';
                    $cart .= '<th>Index</th>';
                    $cart .= '<th>Product</th>';
                    $cart .= '<th>Quantity</th>';
                    $cart .= '<th>Rate</th>';
                    $cart .= '<th class="text-right">Price</th>';
                    $cart .= '<th></th>';
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

                $remove_button = '<form action="#" method="post" >';
                $remove_button .= '<input type="hidden" name="item_name" value="' . $product_name . '" />';
                $remove_button .= '<input type="hidden" name="cart_action" value="remove_all" />';
                $remove_button .= '<button type="submit" title="Remove Item" class="btn btn-default"><i class="fa fa-times" ></i></button>';
                $remove_button .= '</form><br />';

                $cart .= '<tr>';
                $cart .= '<td class="cart-td">' . $index . '</td>';
                $cart .= '<td class="cart-td">' . $product_name . '</td>';
                $cart .= '<td class="cart-td">' . $quantity . '</td>';
                $cart .= '<td class="cart-td">' . $rate . '</td>';
                $cart .= '<td class="cart-td text-right">' . $price . '</td>';
                $cart .= '<td>' . $remove_button . '</td>';
                $cart .= '</tr>';
                $index++;
            }

                /**
                 * Total
                 */
                $cart .= '<tr class="active">';
                $cart .= '<td colspan="2"></td>';
                $cart .= '<td colspan="2"><b>Total</b></td>';
                $cart .= '<td class="text-right cart-td">USD <b>' . $total . '</b></td>';
                $cart .= '<td></td>';
                $cart .= '</tr>';

                /**
                 * Checkout
                 */
                $cart .= '<tr>';
                $cart .= '<td colspan="2"></td>';
                $checkout_image_url = UDS_URL . 'assets/checkout-with-paypal.png';
                $checkout_image = '<img class="img-centered img-responsive" src="' . $checkout_image_url . '" />';
                $checkout_url = '<a href="' . home_url() . '/store/checkout/" title="Checkout with PayPal">' . $checkout_image . '</a>';
                $cart .= '<td colspan="3">' . $checkout_url . '</td>';
                $cart .= '<td></td>';
                $cart .= '</tr>';

            /**
             * Table Footer
             */
            $cart .= '</body>';
            $cart .= '</table></div>';
            $cart .= '<hr />';
        }
        echo $cart;
        ?>
    </div>
    <div class="col-md-4">
        <?php $udssl_theme->sidebar->store_right(); ?>
    </div>
</div>
<?php
get_footer();
?>
