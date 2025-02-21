<?php

namespace Drupal\google_customer_reviews\Plugin\Commerce\CheckoutPane;

use Drupal\commerce_checkout\Plugin\Commerce\CheckoutFlow\CheckoutFlowInterface;
use Drupal\commerce_checkout\Plugin\Commerce\CheckoutPane\CheckoutPaneBase;
use Drupal\commerce_checkout\Plugin\Commerce\CheckoutPane\CheckoutPaneInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the Google Customer Reviews opt-in pane.
 *
 * @CommerceCheckoutPane(
 *   id = "google_customer_reviews_optin",
 *   label = @Translation("Google Customer Reviews opt-in"),
 *   display_label = @Translation("Google Customer Reviews"),
 *   default_step = "complete",
 * )
 */
class GoogleCustomerReviewsOptIn extends CheckoutPaneBase implements CheckoutPaneInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   *
   * @phpstan-ignore missingType.iterableValue ($configuration parameter type should be documented upstream)
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, ?CheckoutFlowInterface $checkout_flow = NULL): static {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition, $checkout_flow);
    $instance->configFactory = $container->get('config.factory');
    return $instance;
  }

  /**
   * {@inheritdoc}
   *
   * @phpstan-ignore-next-line ($pane_form, &$complete_form and return type should be documented upstream)
   */
  public function buildPaneForm(array $pane_form, FormStateInterface $form_state, array &$complete_form) {
    $order = $this->order;
    $config = $this->configFactory->get('google_customer_reviews.settings');
    $delivery_profile = NULL;

    // Retrieve the shipping profile if it exists.
    if ($order->hasField('shipments')) {
      $shipments = $order->get('shipments')->referencedEntities();
      if (count($shipments) > 0) {
        $delivery_profile = $shipments[0]->getShippingProfile();
      }
    }

    // Use the billing profile if there is no shipping profile.
    if (!$delivery_profile) {
      $delivery_profile = $order->getBillingProfile();
    }

    // Delivery country is required. Skip if there is no address to use.
    // @todo Fall back to the store's country if there is no profile availible.
    if ($delivery_profile && $delivery_profile->address->first()) {
      // Set the estimated date to today plus configured number of days.
      $estimated_delivery_date = date('Y-m-d', strtotime('+' . $config->get('estimated_delivery_days') . ' days'));
      $delivery_address = $delivery_profile->address->first();

      $js_settings = [
        'merchant_id' => $config->get('merchant_id'),
        'order_id' => $order->id(),
        'email' => $order->getEmail(),
        'delivery_country' => $delivery_address->getCountryCode(),
        'estimated_delivery_date' => $estimated_delivery_date,
        'opt_in_style' => $config->get('optin_style'),
      ];

      $pane_form['container'] = [
        '#type' => 'container',
        '#attributes' => [
          'id' => 'google-customer-reviews-optin',
        ],
        '#attached' => [
          'library' => ['google_customer_reviews/optin'],
          'drupalSettings' => [
            'googleCustomerReviews' => $js_settings,
          ],
        ],
      ];
    }

    return $pane_form;
  }

}
