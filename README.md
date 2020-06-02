# Plugin Features

The APPROVE Devtools plugin provides the building blocks for integrating KWIPPED APPROVE into any worpress website. Rather than addressing a specific template or e-commerce platform, it is generic and can be used with any template and any e-commerce platform. . 

This progin provides functionality in three different web stack layers. Page level (design), Javascipt (page logic) and PHP code (site programming). 


# TL;DR
For experienced developers.
1. Retrieve your APPROVE id from KWIPPED
2. Download the WordPress Devtools plugin from the dist folder in GitHub
3. Install the plugin into WordPress
4. Set your APPROVE id in the plugin settings. 
5. Utilize the tags, javascript and php library functions provided by the plugin in your website.

#Integration Levels

## Page level tag integration

There are three types of tags that may be used on site pages. Teaser tags, action (button) tags, and "hide" tags.

### Teaser tags

Used to place dynamic teaser rates in web pages.

```html
<span approve-function="teaser_rate" approve-total="30000"></span>
```

The approve-function and approve-total properties are required. The content of the tag bearing these properties will be replaced with the official fiunancing teaser rate for the value of the approve-total property.

### Action (button) tags

```html
<button
        approve-function="hosted_app"
        approve-action="add_to_app"
        approve-model="Model-5"
        approve-price="5000"
        approve-qty="1"
        approve-item-type="new_product"
>Click here to finance a Model-5</button>

```

The approve-function, approve-action, approve-model, approve-price, approve-qty, and approve-item-type properties are required. The content of the tag bearing these properties will not be changed, but an action will be added to the element's click event. 

Note: The only approve-function available in the devtools plugin is "hosted_app" at this time.

### "Hide" tags

These tags are used to hide selected content on the page. 

```html
<approve-hide/>
```

Whe placed no the page, it will cause any other tags containing the "approve-container" property to be hidden. Example:

```html
<div approve-container>
  Lease a decontamination trailler for
  <span approve-function="teaser_rate" approve-total="300000"></span>/mo
</div>

<approve-hide/>
```

In the example above, the "approve-hide" tag will case the "div" containine the finance teaser to be hidden on the page.

## Javascript Integration

Once the devtools plugin is enabled, a kwipped_approve object is avaialble on every page in your site. The following functions are avaialble

### kwipped_approve.get_teaser(value)

Returns a promise, which will provide the teaser rate for the value provided. The following example will print the teaser rate for the value of $35,000 to the console.

```javascript
kwipped_approve.get_teaser(35000)
  .then(function(data){
  	console.log(data)
	})
  .catch(function(error){
  	console.log("An error has occurred."+error)
	});
```

### kwipped_approve.get_approve_information()

Returns an object contaning the URL for the KWIPPED hosted application page, a teaser sentence, and the raw teaser rate.

Return object example:

```javascript
{
    url: "https://example.kwipped.com&items=[{"model":"m3","quantity":"1","type":"new_product","price":"23434"},{"model":"m2","quantity":"1","type":"new_product","price":"2343"},{"model":"m3","quantity":"1","type":"new_product","price":"23434"}]",
   teaser: "Finance for $499/mo",
   teaser_raw: "499"
}
```

In order to use this tag you will have to add items to the kwipped_approve object before calling this function. 

```javascript
kwipped_approve.add_item(model,price,qty,item_type);
```

Example:

```javascript
kwipped_approve.add_item("Model-3",10000,1,"new_product");
```

After adding at least one item, the kwipped_approve.get_approve_information() function will return the correct information for the value provided. The following utilitarian functions are also available:

```javascript
kwipped_approve.get_current_items()
//Returns the list of current items.
kwipped_approve.remove_item(index) 
//Removes item at the provided index
kwipped_approve.clear_items()
//Removes all items currently in the list
```

## PHP code

The same constructs avaialble in javascript are avaialble as PHP functions for sever side processing needs. 

### Approve library instantiation

```php 
use \com\kwipped\approve\wordpress\devtools\Approve;
$app = new Approve();
```

### Adding items and retrieving approve information

After instantiating the Approve class, add items to it in the following fashion.

```php
$app->add($model,$price,$qty,$item_type);
```

Retrieve APPROVE information using the following method:
```php
$app->get_approve_information()
```

Example:
```php
$app->add("Model-1","1000","3","new_product");
$app->add("Model-2","2000","1","new_product");
//Print the returned information to the page.
echo "<pre>";
echo print_r($app->get_approve_information());
echo "</pre>";
```

### Teaser rates

```php
echo "<pre>";
echo print_r($app->get_teaser(25000));
echo "</pre>";
```



# Detailed Installation Instructions

## 1. Retrieve your APPROVE id from KWIPPED
In order to use the APPROVE woocommerce plugin you will need a subscription to the APPROVE lenger network at KWIPPED. For more information please visit www.kwipped.com
1. Log into KWIPPED
2. Visit the APPROVE settings page
3. Copy your APPROVE id

## 2. Download the wordpress plugin from the dist folder in GitHub
1. In the APPROVE Woocommerce plugin page in GitHub (https://github.com/KWIPPED/approve-wordpress-devtools-plugin navigate to the dist folder displayed close to the top of the page. Download the approve-woocommerce-plugin.zip to your computer.

## 3. Install the plugin into Wordpress
In Wordpress navigate to the plugins page. Click on "Add New", then "Upload Plugin"
1. Select the file you downloaded on Section #2
2. The APPROVE Devtools plugin is now installed.

## 4. Set your APPROVE id in the plugin settings
1. In Wordpress, under "Settings" click on "APPROVE Devtools Plugin"
2. Enter your Approve id retrieved in Section #1

# Updates

APPROVE Devtools plugin updates will be released periodically. You may update it by visiting the "Plugins" page in wordpress and following the provided instructions.
