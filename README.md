# Plugin Features

The APPROVE WorPress Plugin provides the KWIPPED APPROVE Plugin in WordPress. You may follow the APPROVE tag standards and build equipment financing
into your site with minimum web design effort. Both the embedded and hosted application models are provided.

# TL;DR
For experienced developers.
1. Retrieve your APPROVE id from KWIPPED
2. Download the APPROVE WorPress Plugin from the dist folder of this application on GitHub
3. Install the plugin into WordPress
4. Set your APPROVE id in the plugin settings. 
5. Insert APPROVE tags into your site as needed.

# Detailed Installation and usage
## Installation
1. Visit the [APPROVE Woocommerce Integration Plugin page on Git Hub.](https://github.com/KWIPPED/approve-wordpress-plugin) 
2. Download and install the plugin, which is avaialble in the [dist folder of the project](https://github.com/KWIPPED/approve-wordpress-plugin/tree/master/dist).
3. Install and activate the plugin in your WordPress site.
----------------------------------------------------------------------------

## Usage

Two versions of APPROVE will be avaialble when the plugin is installe.

1. **Embedded App** - As users interact with your site and select equipment for which they desire financing, the selected equipment is added to a "lease application cart" embedded into your site, which slides in from the right side of the browser.
2. **Hosted App** - As users interact with your site and select equipment for which they desire financing, the selected equipment is added to a "finance application cart" branded as your business, hosted on the APPROVE site.

Both versions will enable the same functionality, which is to provide you with the ability to insert financing options at the point of sale. The functional difference between both is that while the Embedded App keeps users on your page/site, the Hosted App takes users away from your page/site. You may choose the solution you want to use.

Your task is to place "finance rate teasers" where appropriate, and "action buttons", that allows for users to add items to the APPROVE "finance application cart".

### Example Teaser Tag

```html
<span
  approve-function="teaser_rate"
  approve-total="10000"
></span>/mo Apply For Financing
```

The 

The exmaple above will place a finance teaser on your webpage.

### Example Action Tag

``` html
<button
  approve-function="embedded_app"
  approve-action="add_to_app"
  approve-qty="1"
  approve-model="Model-1" 
  approve-item-type="new_product"
   approve-price="1000" 
>Apply For Financing</button>
```

The example above will add One (1) Model-1 item to the "finance application cart", at the price of $1,000.00. The "finance application cart" will slide into view from the right side of the webpage.

# More Information

For a full list of APPROVE tags and functionality please visit the [APPROVE documentation](http://approvedocs.kwipped.com/docs/1.0/approve_web_integration#tags).
