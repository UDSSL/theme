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
        $args = array(
            'post_type' => array('products'),
            'posts_per_page' => -1,
            'order' => 'ASC'
        );
        $store = '<h1 class="text-info text-right">UDSSL Store <i class="fa fa-building fa-2x"></i></h1>';
        $store .= '<ul class="list-group">';
        $the_query = new WP_Query( $args );
        if($the_query->have_posts()){
            while($the_query->have_posts()){
                $the_query->the_post();
                $store .= '<li class="list-group-item">';
                $store .= '<a href="#" ><h4>' . get_the_title() . '</h4></a>';

                $id = get_the_ID();
                $product_data = get_post_meta($id, 'product_data', true);
                global $udssl_theme;
                $store .= '<p>' . $udssl_theme->wptheme->the_content(get_the_content()) . '<br />';

                $store .= '<form action="#" method="post" >';
                $store .= '<input type="hidden" name="item_name" value="' . get_the_title() . '" />';
                $store .= '<input type="hidden" name="cart_action" value="add_one" />';
                $store .= '<button type="submit" title="Add to Cart" class="btn btn-info btn-lg">Add to Cart</button>';
                $store .= '</form><br />';

                $store .= ' <span class="label label-info store-price">Price: ' . $product_data['price'] . '</span> ';
                $store .= ' <span class="label label-success store-downloads">Sales: ' . $product_data['downloads'] . '</span> ';
                $store .= '</div></div></p></li>';
            }
        }
        $store .= '</ul>';
        $store .= '<hr />';
        echo $store;
        ?>
    </div>
    <div class="col-md-4">
        <?php $udssl_theme->sidebar->store_right(); ?>
    </div>
</div>
<?php
get_footer();
exit;
?>
