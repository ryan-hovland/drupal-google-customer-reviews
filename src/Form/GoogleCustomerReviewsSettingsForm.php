<?php

namespace Drupal\google_customer_reviews\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a configuration form for Google Customer Reviews settings.
 */
class GoogleCustomerReviewsSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   *
   * @phpstan-ignore missingType.iterableValue (return type should be documented upstream)
   */
  protected function getEditableConfigNames(): array {
    return ['google_customer_reviews.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'google_customer_reviews_settings';
  }

  /**
   * {@inheritdoc}
   *
   * @phpstan-ignore-next-line ($form and return type should be documented upstream)
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('google_customer_reviews.settings');

    $form['merchant_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Merchant ID'),
      '#description' => $this->t('Get your merchant ID from the Google Merchant Center. It is visible in the upper right section of the screen.'),
      '#required' => TRUE,
      '#default_value' => $config->get('merchant_id'),
    ];

    $form['optin_style'] = [
      '#type' => 'select',
      '#title' => $this->t('Opt-in style'),
      '#description' => $this->t('How you want the review opt-in to appear.'),
      '#options' => [
        'CENTER_DIALOG' => $this->t('Center dialog'),
        'BOTTOM_RIGHT_DIALOG' => $this->t('Bottom right dialog'),
        'BOTTOM_LEFT_DIALOG' => $this->t('Bottom left dialog'),
        'TOP_RIGHT_DIALOG' => $this->t('Top right dialog'),
        'TOP_LEFT_DIALOG' => $this->t('Top left dialog'),
        'BOTTOM_TRAY' => $this->t('Bottom tray'),
      ],
      '#default_value' => $config->get('optin_style') ?: 'CENTER_DIALOG',
    ];

    $form['estimated_delivery_days'] = [
      '#type' => 'number',
      '#title' => $this->t('Estimated days to delivery'),
      '#description' => $this->t('This is how many days Google will wait to contact the customer via email to leave a review after purchase.'),
      '#required' => TRUE,
      '#min' => 1,
      '#default_value' => $config->get('estimated_delivery_days') ?: 0,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   *
   * @phpstan-ignore missingType.iterableValue ($form parameter type should be documented upstream)
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('google_customer_reviews.settings')
      ->set('merchant_id', $form_state->getValue('merchant_id'))
      ->set('optin_style', $form_state->getValue('optin_style'))
      ->set('estimated_delivery_days', $form_state->getValue('estimated_delivery_days'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
