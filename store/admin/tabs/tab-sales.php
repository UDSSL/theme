<?php
if( !defined( 'ABSPATH' ) ){
    header('HTTP/1.0 403 Forbidden');
    die('No Direct Access Allowed!');
}

/**
 * PayPal Settings Section
 */
add_settings_section(
    'udssl_sales',
    __('UDSSL Store Report', 'udssl'),
    'udssl_sales_callback',
    'manage-udssl-store'
);

/**
 * UDSSL Sales Callback
 */
function udssl_sales_callback(){
        echo '<h2>' . __('Item Sales Report', 'udssl') . '</h2>';
        settings_errors();
            echo '<table class="widefat" style="margin-top: 10px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Token</th>
                        <th>Book</th>
                        <th>Payment Time</th>
                        <th>State</th>
                        <th>Amount</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Payer ID</th>
                        <th>Remaining</th>
                        <th>Link</th>
                    </tr>
                </thead>
                <tbody>';

            global $udssl_theme;
            $records = $udssl_theme->store->database->get_all_records();
            if($records){
                foreach($records as $sale):
                    $item = get_post($sale['item_id']);
                    $item_name = $item->post_title;
                    $item_page = $item->guid;
                    $item_edit_page = admin_url('post.php?post=' . $sale['item_id'] . '&action=edit');
                    $item_url  = '<a target="_blank" href="' . $item_page . '" >' . $item_name . '</a> | ';
                    $item_url .= '<a target="_blank" href="' . $item_edit_page . '" >Edit</a> ';
                    $link = '<a target="_blank" href="' . get_home_url() . '/downloads/' . $sale['link'] . '/" >Download</a> ';
                    echo'<tr>
                        <td>' . $sale['id'] . '</td>
                        <td>' . $sale['token'] . '</td>
                        <td>' . $item_url . '</td>
                        <td>' . $sale['update_time'] . '</td>
                        <td>' . $sale['state'] . '</td>
                        <td>' . $sale['amount'] . '</td>
                        <td>' . $sale['first_name'] . '</td>
                        <td>' . $sale['last_name'] . '</td>
                        <td>' . $sale['email'] . '</td>
                        <td>' . $sale['payer_id'] . '</td>
                        <td>' . $sale['count'] . '</td>
                        <td>' . $link . '</td>
                        </tr>';
                endforeach;
            }
            echo '</tbody><tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Token</th>
                        <th>Book</th>
                        <th>Payment Time</th>
                        <th>State</th>
                        <th>Amount</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Payer ID</th>
                        <th>Remaining</th>
                        <th>Link</th>
                    </tr>
                </tfoot>';

            echo '</table>';
        echo '</div>';
}
?>
