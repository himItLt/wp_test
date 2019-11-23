<?php
function enqueue_parent_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
/**
 * Show prod extra data
 *
 * @param integer $prodId
 */
function ttwc_show_view( $prodId ) {
    $views = intval(get_post_meta($prodId, 'ttwc_product_views', true));
    echo '<div><b>Total views: </b>' . $views . '</div>';

    $lastPurchase = intval(get_post_meta($prodId, 'ttwc_product_last_purchase', true));
    $date = $lastPurchase ? date(get_option('date_format') . ' ' . get_option('time_format'), $lastPurchase) : 'Unknown';
    echo '<div><b>Last purchase: </b>' . $date . '</div>';
}
add_action('ttwc_show_view', 'ttwc_show_view');
/**
 * Inc prod views counter
 *
 * @param $prodId
 */
function ttwc_inc_view( $prodId ) {
    $views = intval(get_post_meta($prodId, 'ttwc_product_views', true));
    update_post_meta($prodId, 'ttwc_product_views', $views + 1);
}
add_action('ttwc_inc_view', 'ttwc_inc_view');
/**
 * Mark all products which were purchased with last date option
 * @param $orderId
 */
function ttwc_payment_complete( $orderId ) {
    $order = new WC_Order( $orderId );
    $items = $order->get_items();

    foreach ( $items as $item ) {
        $productId = $item['product_id'];
        update_post_meta($productId, 'ttwc_product_last_purchase', time());
    }
}
add_action('woocommerce_payment_complete', 'ttwc_payment_complete');

