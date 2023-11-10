<?php

namespace Drupal\randstad_test\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\randstad_test\RegistrationCountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Registration count' block.
 *
 * @Block(
 *   id = "registration_count",
 *   admin_label = @Translation("Registration count")
 * )
 */
class RegistrationCountBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The registration count service.
   *
   * @var \Drupal\randstad_test\RegistrationCountInterface
   */
  protected RegistrationCountInterface $registrationCount;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
    );
    $instance->registrationCount = $container->get('randstad_test.count_registrations');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [
      '#cache' => [
        'tags' => ['node_list:registration'],
      ],
    ];

    $build['registrations_number'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#value' => $this->t('@count employees registered for the event.',  [
        '@count' => $this->registrationCount->countRegistrations(),
      ]),
    ];

    return $build;
  }

}
