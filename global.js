var approve = approve || {};
approve.ajax_url = php_vars.ajax_url
approve.mode = null;
approve.cart_is_present = false;


//********************************************************************
//* Woocommerce hide functino
//********************************************************************
approve.check_hide_tags = function(){
	if(jQuery('approve-hide').length>0){
		jQuery('[approve-container]').hide();
	}
}


//********************************************************************
//* Teaser tags in which the approve-total is set. 
//* These values OVERRIDE other vlaues on the page.
//********************************************************************
approve.update_custom_teaser_tags = function(){
	//Teaser rate tags with approve-total entries will be processed on the page no matter what. If they esxist and they have a value, then 
	//we will use that value to get a teaser.
	jQuery('[approve-function="teaser_rate"][approve-total]').each(function(){
		var value = parseFloat(jQuery(this).attr('approve-total'));
		if(isNaN(value)){
			jQuery(this).html("ATTENTION! The approve total parameter must contain a number.");
			return;
		}
		//If you are here we have a usable value;
		var info = {value:value};
		var data = {action: "get_approve_teaser_custom",data:info};
		var ref = this;
		jQuery.post(approve.ajax_url,data, function(response) {
			jQuery(ref).html(response);
		});
	});
}

//********************************************************************
//* Action tags in which the model,qty,price and type are set. 
//* These values OVERRIDE other vlaues on the page.
//********************************************************************
approve.update_static_buttons = function(){
	jQuery('[approve-function="hosted_app"][approve-action="add_to_app"]:not([approve-woocommerce-product]').each(function(){
		var ref = jQuery(this);
		var model = jQuery(this).attr('approve-model');
		var item_type = jQuery(this).attr('approve-item-type');
		var qty = jQuery(this).attr('approve-qty');
		var price = parseFloat(jQuery(this).attr('approve-price'));
		var errors = "";
		var separator="";
		if(!model){
			errors = errors+separator+"The approve-model property is required.";
			separator=" ";
		}
		if(!item_type){
			errors = errors+separator+"The approve-item-type property is required.";
			separator=" ";
		}
		if(!qty){
			errors = errors+separator+"The approve-qty property is required.";
			separator=" ";
		}

		if(isNaN(price)){
			errors = errors+separator+"The approveprice property must be a number.";
		}

		if(errors){
			errors = "Please address the following errors: "+errors;
			jQuery(this).html(errors);
			return;
		}

		var info = {
			model:model,
			item_type:item_type,
			qty:qty,
			price:price
		};
		var data = {action: "get_static_button_action",data:info};
		jQuery.post(approve.ajax_url,data, function(response) {
			//console.log(response);
			var url = response.url;
				ref.off('click');
				ref.click(function(){
					window.open(url);
				})
		});
	});
}

//********************************************************************
//* Dynamically retreieves WOOCOMMERCE information and updates
//* pertinent tags on the screen. 
//********************************************************************
approve.update_approve_woocommerce_tags = function(){
	//****************************************
	//* If there is a product on the screen.
	//****************************************
	if(approve.mode){
		var info = this.get_woocart_information();
		var data = {action: "get_approve_teaser",data:info};
		jQuery.post(approve.ajax_url,data, function(response) {
			//************************************
			// jQuery('[approve-product-button-variable]').each(function(){
			// 	var url = response.url;
			// 	jQuery(this).html(response.teaser);
			// 	jQuery(this).off('click');
			// 	jQuery(this).click(function(){
			// 		window.open(url);
			// 	})
			// });
			// jQuery('[approve-product-button-simple]').each(function(){
			// 	var url = response.url;
			// 	jQuery(this).html(response.teaser);
			// 	jQuery(this).off('click');
			// 	jQuery(this).click(function(){
			// 		window.open(url);
			// 	})
			// });
			//*************************************
			//Ignore all teaser rate tags with approve total entries. They will be processed separately.
			jQuery('[approve-function="teaser_rate"]:not([approve-total])').each(function(){
				var url = response.url;
				jQuery(this).html(response.teaser_raw);
			});
			jQuery('[approve-function="hosted_app"][approve-action="add_to_app"][approve-woocommerce-product="simple"]').each(function(){
				var url = response.url;
				jQuery(this).off('click');
				jQuery(this).click(function(){
					window.open(url);
				})
			});
			jQuery('[approve-function="hosted_app"][approve-action="add_to_app"][approve-woocommerce-product="variable"]').each(function(){
				var url = response.url;
				jQuery(this).off('click');
				jQuery(this).click(function(){
					window.open(url);
				})
			});
		});
	}

	//****************************************
	//* If there is a cart on the screen.
	//****************************************
	if(approve.cart_is_present){
		var data = {'action': 'get_approve_information'};
		jQuery.post(approve.ajax_url,data, function(response) {
			var url = response.url;
			jQuery('[approve-cart-button]').each(function(){
				jQuery(this).html(response.teaser);
				jQuery(this).off('click');
				jQuery(this).click(function(){
					window.open(url);
				})
			});
		});
	}
}

jQuery(document).ready(function(){
	//**********************************************
	//Which type of product do we have on the screen?
	//**********************************************
	if(jQuery('[approve-product-button-simple]').length>0) approve.mode="simple";
	else if(jQuery('[approve-product-button-variable]').length>0) approve.mode="variable";

	if(approve.mode=="simple"){
		approve.get_woocart_information = approve.get_woocart_information_simple;
	}
	else if(approve.mode=="variable"){
		approve.get_woocart_information = approve.get_woocart_information_variable;
	}

	//**********************************************

	if(jQuery('[approve-cart-button]').length>0) approve.cart_is_present = true;

	approve.update_approve_woocommerce_tags();
	//For product pages
	jQuery('form.variations_form').on('woocommerce_variation_has_changed', function(){
		approve.update_approve_woocommerce_tags();
	});
	//For cart.
	jQuery(document.body).on('updated_cart_totals', function(){
		approve.update_approve_woocommerce_tags();
	});

	approve.update_custom_teaser_tags();
	approve.update_static_buttons();
	approve.check_hide_tags();
});

/**
 * Uses woocommerce standard cart.
 */
approve.get_woocart_information_variable = function(){
	var info = {"model":null,"price":null};
	model = jQuery('.product_title').text();
	info.model = model;
	price = jQuery('.woocommerce-variation-price .amount').text().replace(/ /g,'').replace(/\$/g,'').replace(/,/g,''),
	parsePrice  = parseFloat(price),
	totalPrice  = parsePrice.toFixed(2);
	if(!(totalPrice=="NaN")){
		info.price = totalPrice;
	}
	return info;
}

approve.get_woocart_information_simple = function(){
	var info = {"model":null,"price":null};

	//We will get the price from the structured data avaialble on the page. This was better then 
	//trying to parse HTML to try and see if they had included a sale price, etc.
	//var jsonld = JSON.parse(document.querySelector('script[type="application/ld+json"]').innerText);
	jQuery("[type='application/ld+json']").each(function(){
		try{
			// if(jsonld["@graph"][1] && jsonld["@graph"][1]['@type']=="Product"){
			// 	info.price = jsonld["@graph"][1].offers[0].price;
			// 	info.model = jsonld["@graph"][1].name;
			// }

			var jsonld = JSON.parse(jQuery(this).html());
			if(jsonld["@graph"].length){
				for (var j=0; j<jsonld["@graph"].length; j++){
					if(jsonld["@graph"][j]['@type']=="Product"){
						info.price = jsonld["@graph"][j].offers[0].price;
						info.model = jsonld["@graph"][j].name;
						break;
					}
				}
			}
		}
		catch(error){
			console.error("The APPROVE plugin could not parse the page.");
		}
	});
	return info;
}