var kwipped_approve = kwipped_approve || {};
kwipped_approve.ajax_url = php_vars.ajax_url
kwipped_approve.current_items = [];


//Available on the page. Example call
//kwipped_approve.get_teaser("sdfsdfsd").then(function(data){console.log(data)}).catch(function(error){console.log("BOO BOO"+error)});
kwipped_approve.get_teaser=function(value){
	var self = this;
	return new Promise(function(resolve,reject){
		var info = {value:value};
		var data = {action: "get_approve_teaser",data:info};
		jQuery.ajax({
			url:self.ajax_url,
			type:'POST',
			data,
			success:function(data){
				resolve(data);
			},
			error:function(error){
				resolve(reject);
			}
		});
	});
}

kwipped_approve.add_item=function(model,price,qty,type){
	var errors = "";
	var separator = "";
	if(!model){
		errors = errors+separator+"The model field cannot be empty.";
		separator=" ";
	}
	if(!price){
		errors = errors+separator+"The price field cannot be empty.";
		separator=" ";
	}
	price = parseFloat(price);
	if(isNaN(price)){
		errors = errors+separator+"The price field cannot must be a number.";
		separator=" ";
	}
	if(!qty){
		errors = errors+separator+"The qty field cannot be empty.";
		separator=" ";
	}
	if(!type){
		errors = errors+separator+"The type field cannot be empty.";
		separator=" ";
	}
	if(errors){
		return {
			success:false,
			message:errors
		}
	}
	this.current_items.push({
		model:model,
		price:price,
		qty:qty,
		type:type
	});
	return {
		success:true
	}
}

kwipped_approve.remove_item=function(index){
	this.current_items.splice(index,1);
}

kwipped_approve.clear_items=function(){
	this.current_items = [];
}

kwipped_approve.get_approve_information = function(){
	var self = this;
	return new Promise(function(resolve,reject){
		var info = {items:self.current_items};
		var data = {action: "get_approve_information",data:info};
		jQuery.ajax({
			url:self.ajax_url,
			type:'POST',
			data,
			success:function(data){
				resolve(data);
			},
			error:function(error){
				resolve(reject);
			}
		});
	});
}

kwipped_approve.get_current_items = function(){
	return this.current_items;
}


//********************************************************************
//* Woocommerce hide functino
//********************************************************************
kwipped_approve.check_hide_tags = function(){
	if(jQuery('approve-hide').length>0){
		jQuery('[approve-container]').hide();
	}
}

//********************************************************************
//* Teaser tags in which the approve-total is set. 
//* These values OVERRIDE other vlaues on the page.
//********************************************************************
kwipped_approve.update_teaser_tags = function(){
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
		var data = {action: "get_approve_teaser",data:info};
		var ref = this;
		jQuery.post(kwipped_approve.ajax_url,data, function(response) {
			jQuery(ref).html(response);
		});
	});
}

//********************************************************************
//* Action tags in which the model,qty,price and type are set. 
//* These values OVERRIDE other vlaues on the page.
//********************************************************************
kwipped_approve.update_buttons = function(){
	jQuery('[approve-function="hosted_app"][approve-action="add_to_app"]').each(function(){
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
		var data = {action: "get_button_action",data:info};
		jQuery.post(kwipped_approve.ajax_url,data, function(response) {
			//console.log(response);
			var url = response.url;
				ref.off('click');
				ref.click(function(){
					window.open(url);
				})
		});
	});
}

jQuery(document).ready(function(){
	kwipped_approve.update_teaser_tags();
	kwipped_approve.update_buttons();
	kwipped_approve.check_hide_tags();
});