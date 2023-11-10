<?php

namespace Drupal\randstad_test;

/**
 * Service to count the registrations number.
 */
class RegistrationCount implements RegistrationCountInterface {

  /**
   * {@inheritdoc}
   */
  public function countRegistrations(): int {
    $number = 0;
    $query = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->getQuery();

    $query->accessCheck();
    $query->condition('type', 'registration');
    $query->count();

    return $query->execute();
  }

}
