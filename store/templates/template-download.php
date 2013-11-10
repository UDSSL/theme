<?php
/**
 * UDSSL Downloads Template
 */
get_header();
global $udssl_theme;
?>
<div class="row">
    <div class="col-md-8">
        <?php
        if(!isset($downloads) || sizeof($downloads) == 0){
            $cart = 'No Downloads Available';
        } else {
            $cart = '<h1 class="text-info"><i class="fa fa-download fa-2x"></i> UDSSL Downloads</h1>';
            $cart .= '<hr />';

            /**
             * Table Header
             */
            $cart .= '<table id="udssl-cart" class="table table-responsive table-hover">';
            $cart .= '<thead>';
                $cart .= '<tr>';
                    $cart .= '<th>Index</th>';
                    $cart .= '<th>Product</th>';
                    $cart .= '<th>Quantity</th>';
                    $cart .= '<th>Rate</th>';
                    $cart .= '<th>Price</th>';
                    $cart .= '<th>Download</th>';
                $cart .= '</tr>';
            $cart .= '</thead>';
            $cart .= '<body>';

            $index = 1;
            $total = 0;
            foreach($downloads as $download){
                $rate = $download['rate'];
                $price = $rate * $download['quantity'];
                $total += $price;

                $download_button = '<a href="' . get_home_url() . '/downloads/' . $download['link'] . '/" class="btn btn-primary btn-sm" ><i class="fa fa-download"></i> Download</a>';

                $cart .= '<tr>';
                $cart .= '<td>' . $index . '</td>';
                $cart .= '<td>' . $download['name'] . '</td>';
                $cart .= '<td>' . $download['quantity'] . '</td>';
                $cart .= '<td>' . $rate . '</td>';
                $cart .= '<td>' . $price . '</td>';
                $cart .= '<td>' . $download_button . '</td>';
                $cart .= '</tr>';
                $index++;
            }

                /**
                 * Total
                 */
                $cart .= '<tr>';
                $cart .= '<td colspan="3"></td>';
                $cart .= '<td colspan="2"><b>Total</b></td>';
                $cart .= '<td><b>' . $total . ' USD</b></td>';
                $cart .= '</tr>';

            /**
             * Table Footer
             */
            $cart .= '</body>';
            $cart .= '</table>';
            $cart .= '<hr />';
            echo $cart;
        }

        ?>
    </div>
    <div class="col-md-4">
        <?php $udssl_theme->sidebar->store_right(); ?>
    </div>
</div>
<?php
get_footer();
?>
