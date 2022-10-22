<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://profiles.wordpress.org/rahulharkhani/
 * @since      1.0.0
 *
 * @package    Woocommerce_Quick_Order
 * @subpackage Woocommerce_Quick_Order/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="product-list-table">
	<table class="product-list" id="product-list">
		<thead>
		<tr>
			<th><?php esc_html_e( '', WOOCOMMERCE_QUICK_ORDER_TEXT_DOMAIN ); ?></th>
			<th><?php esc_html_e( 'No', WOOCOMMERCE_QUICK_ORDER_TEXT_DOMAIN ); ?></th>
			<th><?php esc_html_e( 'Product Image', WOOCOMMERCE_QUICK_ORDER_TEXT_DOMAIN ); ?></th>
			<th><?php esc_html_e( 'Product Title', WOOCOMMERCE_QUICK_ORDER_TEXT_DOMAIN ); ?></th>
			<th><?php esc_html_e( 'Product Price', WOOCOMMERCE_QUICK_ORDER_TEXT_DOMAIN ); ?></th>
		</tr>
		</thead>
		<tbody>
			<?php
			if ( $productLists->have_posts() ) :
				$count = 1;
				while ( $productLists->have_posts() ) : $productLists->the_post();
					$productID = get_the_ID();
					$_product = wc_get_product( $productID );
					$price = $_product->get_regular_price();
					$image_id  = $_product->get_image_id();
					$image_url = (wp_get_attachment_image_url( $image_id, 'thumbnail' )) ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : site_url()."/wp-content/plugins/".WOOCOMMERCE_QUICK_ORDER_TEXT_DOMAIN."/public/image/woocommerce-placeholder-150x150.png";
					?>
					<tr>
						<td>
							<input type="checkbox" name="productids" id="productids" value="<?php echo $productID; ?>" title="<?php echo get_the_title($productID); ?>" price="<?php echo $_product->get_price(); ?>" currency="<?php echo get_woocommerce_currency_symbol(); ?>" />
						</td>
						<td><?php echo $count; ?></td>
						<td>
							<img src="<?php  echo $image_url; ?>" data-id="<?php echo $productID; ?>">
						</td>
						<td>
							<a href="<?php echo get_permalink( $productID ); ?>" target="_blank">
								<?php echo get_the_title($productID); ?>
							</a>
						</td>
						<td>
							<?php echo $_product->get_price_html(); ?>
						</td>
					</tr>
					<?php
					$count++;
				endwhile;
			else :
				include 'product-not-found.php';
			endif;
			?>
		</tbody>
	</table>
</div>