(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	/**
	 * Define a function to navigate betweens form steps.
	 * It accepts one parameter. That is - step number.
	*/

	$( window ).load(function() {

		const navigateToFormStep = (stepNumber) => {

			// Hide all form steps.
			document.querySelectorAll(".form-step").forEach((formStepElement) => {
				formStepElement.classList.add("d-none");
			});

			// Mark all form steps as unfinished.
			document.querySelectorAll(".form-stepper-list").forEach((formStepHeader) => {
				formStepHeader.classList.add("form-stepper-unfinished");
				formStepHeader.classList.remove("form-stepper-active", "form-stepper-completed");
			});
			document.querySelector("#step-" + stepNumber).classList.remove("d-none");
			
			// Select the form step circle (progress bar).
			const formStepCircle = document.querySelector('li[step="' + stepNumber + '"]');
			
			formStepCircle.classList.remove("form-stepper-unfinished", "form-stepper-completed");
			formStepCircle.classList.add("form-stepper-active");
			
			/**
			 * Loop through each form step circles.
			 * This loop will continue up to the current step number.
			 * Example: If the current step is 3,
			 * then the loop will perform operations for step 1 and 2.
			 */
			for (let index = 0; index < stepNumber; index++) {
				const formStepCircle = document.querySelector('li[step="' + index + '"]');
				if (formStepCircle) {
					formStepCircle.classList.remove("form-stepper-unfinished", "form-stepper-active");
					formStepCircle.classList.add("form-stepper-completed");
				}
			}
		};

		const checkValidation = (stepNumber) => {

			if(stepNumber == 2) {
				let firstname = $("#firstname").val();
				let lastname = $("#lastname").val();
				let email = $("#email").val();
				let phone = $("#phone").val();
				let address = $("#address").val();
				let postcode = $("#postcode").val();
				let city = $("#city").val();
				let country = $("#country").val();
				$(".errForm").hide();

				$("#lblFirstname").html(firstname);
				if(firstname == "" || firstname == null) {
					$(".errForm").show();
					return false;
				}
				$("#lblLastname").html(lastname);
				if(lastname == "" || lastname == null) {
					$(".errForm").show();
					return false;
				}
				$("#lblEmail").html(email);
				if(email == "" || email == null) {
					$(".errForm").show();
					return false;
				}
				$("#lblPhone").html(phone);
				if(phone == "" || phone == null) {
					$(".errForm").show();
					return false;
				}
				$("#lblAddress").html(address);
				if(address == "" || address == null) {
					$(".errForm").show();
					return false;
				}
				$("#lblPostcode").html(postcode);
				if(postcode == "" || postcode == null) {
					$(".errForm").show();
					return false;
				}
				$("#lblCity").html(city);
				if(city == "" || city == null) {
					$(".errForm").show();
					return false;
				}
				$("#lblCountry").html(country);
				if(country == "" || country == null) {
					$(".errForm").show();
					return false;
				}
			}

			if(stepNumber == 3) {
				$(".errProductForm").hide();
				let productIds = [];
				let outputProductDetail = '';
				let outputOrderDetail = '';
				let currency = '';
				let total = 0;
				$("input:checkbox[name=productids]:checked").each(function(){
					let productDetail = new Object;
					productDetail['id'] = $(this).val();
					productDetail['title'] = $(this).attr('title');
					productDetail['price'] = $(this).attr('price');
					productDetail['currency'] = $(this).attr('currency');
					productIds.push(productDetail);
					currency = $(this).attr('currency');
					total += parseFloat($(this).attr('price'));
					outputProductDetail += '<tr class="cart_item"><td class="product-name">' + $(this).attr('title') + '</td><td class="product-price">' + $(this).attr('currency') + $(this).attr('price') + '</td>' +
							'<td class="product-total"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">' + $(this).attr('currency') + '</span>' + $(this).attr('price') + '</bdi></span></td>' +
						'</tr>';
				});
				outputOrderDetail = '<tr class="cart-subtotal"><th colspan="2" class="text-right">Subtotal</th><td><span class="woocommerce-Price-amount amount bold"><bdi><span class="woocommerce-Price-currencySymbol">' + currency + '</span>' + total.toFixed(2) + '</bdi></span></td></tr>' +
						'<tr class="order-total"><th colspan="2" class="text-right">Total</th><td><strong><span class="woocommerce-Price-amount amount bold"><bdi><span class="woocommerce-Price-currencySymbol">' + currency + '</span>' + total.toFixed(2) + '</bdi></span></strong> </td></tr>';
				$(".product-details").html(outputProductDetail);
				$(".order-details").html(outputOrderDetail);
				if(productIds.length == 0) {
					$(".errProductForm").show();
					return false;
				}
			}

			return true;
			
		};

		// Select all form navigation buttons, and loop through them.
		document.querySelectorAll(".btn-navigate-form-step").forEach((formNavigationBtn) => {

			// Add a click event listener to the button.
			formNavigationBtn.addEventListener("click", () => {
				// Get the value of the step.
				const stepNumber = parseInt(formNavigationBtn.getAttribute("step_number"));

				if(checkValidation(stepNumber)) {
					// Call the function to navigate to the target form step.
					navigateToFormStep(stepNumber);
				}
			});
		});

		// Click on save button generate order
		$(".submit-btn").click(function() {
			$(".processing").show();
			$(".processing").parent().css('opacity', '0.5');
			let userid = $("#userid").val();
			let firstname = $("#firstname").val();
			let lastname = $("#lastname").val();
			let email = $("#email").val();
			let phone = $("#phone").val();
			let address = $("#address").val();
			let postcode = $("#postcode").val();
			let city = $("#city").val();
			let country = $("#country").val();
			let productIds = [];
			$("input:checkbox[name=productids]:checked").each(function(){
				let productDetail = new Object;
				productDetail['id'] = $(this).val();
				productDetail['title'] = $(this).attr('title');
				productDetail['price'] = $(this).attr('price');
				productDetail['currency'] = $(this).attr('currency');
				productIds.push(productDetail);
			});

			let ajaxurl = $("#adminurl").val();
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				async: false,
				global: false,
				dataType: 'html',
				data: { action: "woo_quick_order_create", userid : userid, firstname : firstname, lastname : lastname, email : email, phone : phone, address : address, postcode : postcode, city : city, country : country, productIds : productIds},
				success: function(data) {
					let obj = JSON.parse(data);
					if(obj.status == 1) {
						$(".processing").hide();
						$(".processing").parent('div').css('opacity', '1');
						alert("Order place successfully");
						$("#lblFirstname").html("");
						$("#lblLastname").html("");
						$("#lblEmail").html("");
						$("#lblAddress").html("");
						$("#lblPhone").html("");
						$("#lblPostcode").html("");
						$("#lblCity").html("");
						$("#lblCountry").html("");
						$(".product-details").html("");
						$(".order-details").html("");
						$("input:checkbox[name=productids]:checked").each(function(){
							$(this).removeAttr('checked');
						});
						window.open(obj.order_url, '_blank');
						navigateToFormStep(1);
					}
				}
			});
		});
		
	});

})( jQuery );