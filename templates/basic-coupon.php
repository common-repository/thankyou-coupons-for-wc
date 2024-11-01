<?php

/**
 * Template : Basic Coupon
 *
 * The template to display a basic coupon
 */

$coupon_colour  = $args['coupon_colour'];
$coupon_message = $args['coupon_message'];
$coupon_expiry  = $args['coupon_expiry'];
$coupon_code    = $args['coupon_code'];
?>
<div id="wctc-coupon" class="woocommerce wctc-coupon-outer" style="color:<?php echo esc_attr( $coupon_colour ); ?>;">
	<div class="wctc-coupon-inner">
		<p class="woocommerce wctc-coupon-message">
			<?php echo $coupon_message; ?>
		</p>
		<span class="woocommerce wctc-coupon-code">
			<?php echo $coupon_code; ?>
		</span>
		<p class="woocommerce wctc-coupon-expiry">
			<?php echo $coupon_expiry; ?>
		</p>
	</div>

</div>
