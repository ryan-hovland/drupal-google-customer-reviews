# Google Customer Reviews

Integrates Google Customer Reviews into your Drupal site.

## Features
* Optional block to place the Badge anywhere on your site.
* Checkout pane to display survey opt-in and fill order details in survey.

## Installation

1. Run `composer require drupal/google_customer_reviews`
2. Install the module `drush pm:enable google_customer_reviews`
3. Clear the cache `drush cr`

## Usage
Get your merchant ID from the Google Merchant Center https://merchants.google.com/. It is visible in the upper right of the screen.

Configure the module with your merchant ID at /admin/config/services/google-customer-reviews
Configure how you want the opt-in to appear and input an average delivery time. This is how many days Google will wait to contact the customer via email to leave a review after purchase.

Place the Google Customer Reviews Badge block in your site's block layout to display your website's current Google Customer Reviews rating.

The checkout pane will automatically be placed on the complete step of your default checkout flow. It will need to be manually placed in other checkout panes.
