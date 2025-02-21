<?php

namespace Drupal\google_customer_reviews\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Google Customer Reviews badge block.
 *
 * @Block(
 *   id = "google_customer_reviews_badge",
 *   admin_label = @Translation("Google Customer Reviews Badge"),
 *   category = @Translation("Google Customer Reviews")
 * )
 */
class GoogleCustomerReviewsBadgeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new GoogleCustomerReviewsBadgeBlock.
   *
   * @param array<string, mixed> $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   *
   * @phpstan-ignore missingType.iterableValue ($configuration parameter type should be documented upstream)
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * Builds the Google Customer Reviews badge block.
   *
   * @return array<string, mixed>
   *   A render array.
   */
  public function build(): array {
    $config = $this->configFactory->get('google_customer_reviews.settings');

    return [
      '#type' => 'inline_template',
      '#template' => '<g:ratingbadge merchant_id="{{ merchant_id }}"></g:ratingbadge>',
      '#context' => [
        'merchant_id' => $config->get('merchant_id'),
      ],
      '#attached' => [
        'library' => ['google_customer_reviews/badge'],
      ],
    ];
  }

}
