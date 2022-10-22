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
<div id="multi-step-form-container">

	<!-- Form Steps / Progress Bar -->
	<ul class="form-stepper form-stepper-horizontal text-center mx-auto pl-0">
		
		<!-- Step 1 -->
		<li class="form-stepper-active text-center form-stepper-list" step="1">
			<a class="mx-2">
				<span class="form-stepper-circle">
					<span>1</span>
				</span>
				<div class="label">Customer Details</div>
			</a>
		</li>
		
		<!-- Step 2 -->
		<li class="form-stepper-unfinished text-center form-stepper-list" step="2">
			<a class="mx-2">
				<span class="form-stepper-circle text-muted">
					<span>2</span>
				</span>
				<div class="label text-muted">Product List</div>
			</a>
		</li>
		
		<!-- Step 3 -->
		<li class="form-stepper-unfinished text-center form-stepper-list" step="3">
			<a class="mx-2">
				<span class="form-stepper-circle text-muted">
					<span>3</span>
				</span>
				<div class="label text-muted">Order Details</div>
			</a>
		</li>

	</ul>

	<!-- Step Wise Form Content -->
	<form id="wooQuickOrderForm" name="wooQuickOrderForm" enctype="multipart/form-data" method="POST">

		<!-- Step 1 Content -->
		<section id="step-1" class="form-step">
			<?php
			global $current_user;
			$userid = 0;
			$firstname = "";
			$lastname = "";
			$email = "";
			$phone = "";
			$address = "";
			$postcode = "";
			$city = "";
			$country = "";

			if ( is_user_logged_in() ) {
				$userid = $current_user->ID;
				$firstname = $current_user->user_firstname;
				$lastname = $current_user->user_lastname;
				$email = $current_user->user_email;
				$phone = get_user_meta( $current_user->ID, 'billing_phone', true );
				$address = get_user_meta( $current_user->ID, 'billing_address_1', true );
				$postcode = get_user_meta( $current_user->ID, 'billing_postcode', true );
				$city = get_user_meta( $current_user->ID, 'billing_city', true );
				$country = get_user_meta( $current_user->ID, 'billing_country', true );
			}
			?>
			<h2 class="font-normal">Customer Details</h2>
			<hr>
			
			<!-- Step 1 input fields -->
			<div class="mt-3">
				<label for="fname">First name *</label>
				<input type="text" id="firstname" name="firstname" placeholder="First Name*" value="<?php echo $firstname; ?>" />
			
				<label for="lname">Last name *</label>
				<input type="text" id="lastname" name="lastname" placeholder="Last Name*" value="<?php echo $lastname; ?>" />
			
				<label for="email">Email *</label>
				<input type="email" id="email" name="email" placeholder="Email*" value="<?php echo $email; ?>" />
			
				<label for="phone">Phone *</label>
				<input type="text" id="phone" name="phone" placeholder="Phone*" value="<?php echo $phone; ?>" />
			
				<label for="address">Address *</label>
				<input type="text" id="address" name="address" placeholder="Address*" value="<?php echo $address; ?>" />
			
				<label for="postcode">Postcode *</label>
				<input type="text" id="postcode" name="postcode" placeholder="Postcode*" value="<?php echo $postcode; ?>" />
			
				<label for="city">City *</label>
				<input type="text" id="city" name="city" placeholder="City*" value="<?php echo $city; ?>" />
			
				<label for="country">Country *</label>
				<input type="text" id="country" name="country" placeholder="Country*" value="<?php echo $country; ?>" />
			</div>
			<div class="errForm">
				<span>* must enter all details</span>
			</div>
			<hr class="mt-3">
			<div class="mt-3">
				<button class="button btn-navigate-form-step" type="button" step_number="2">Next</button>
			</div>
		</section>

		<!-- Step 2 Content, default hidden on page load. -->
		<section id="step-2" class="form-step d-none">
			<h2 class="font-normal">Product List</h2>
			<hr>

			<!-- Step 2 input fields -->
			<div class="mt-3">
				<?php include 'product-list.php'; ?>
			</div>
			<div class="errProductForm">
				<span>* must select product</span>
			</div>
			<hr class="mt-3">
			<div class="mt-3">
				<button class="button btn-navigate-form-step" type="button" step_number="1">Prev</button>
				<button class="button btn-navigate-form-step" type="button" step_number="3">Next</button>
			</div>
		</section>

		<!-- Step 3 Content, default hidden on page load. -->
		<section id="step-3" class="form-step d-none">
			<h2 class="font-normal">Order Details</h2>
			<hr>

			<!-- Step 3 input fields -->
			<div class="mt-3">
				<h5>Customer Information</h4>
				<table class="customer-info">
					<tbody>
						<tr>
							<td><p><strong>Firstname:</strong> <span id="lblFirstname"></span></p></td>
							<td><p><strong>Lastname:</strong> <span id="lblLastname"></span></p></td>
						</tr>
						<tr>
							<td colspan="2"><p><strong>Email:</strong> <span id="lblEmail"></span></p></td>
						</tr>
						<tr>
							<td colspan="2"><p><strong>Address:</strong> <span id="lblAddress"></span></p></td>
						</tr>
						<tr>
							<td><p><strong>Phone:</strong> <span id="lblPhone"></span></p></td>
							<td><p><strong>Postcode:</strong> <span id="lblPostcode"></span></p></td>
						</tr>
						<tr>
							<td><p><strong>City:</strong> <span id="lblCity"></span></p></td>
							<td><p><strong>Country:</strong> <span id="lblCountry"></span></p></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mt-3">
				<h5>Order Information</h4>
				<table class="order-info">
					<thead class="bgcolor">
						<tr>
							<th>Product Title</th>
							<th>Price</th>
							<th>Subtotal</th>
						</tr>
					</thead>
					<tbody class="product-details">
					</tbody>
					<tfoot class="order-details">
					</tfoot>
				</table>
			</div>
			<hr class="mt-3">
			<div class="mt-3">
				<input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>" />
				<input type="hidden" name="adminurl" id="adminurl" value="<?php echo admin_url('admin-ajax.php'); ?>" />
				<button class="button btn-navigate-form-step" type="button" step_number="2">Prev</button>
				<button class="button submit-btn" type="button">Place Order</button>
				<span class="processing">Processing...</span>
			</div>
		</section>

	</form>
</div>