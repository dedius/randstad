<?php

namespace Drupal\randstad_test;

/**
 * Count the registrations number interface.
 */
interface RegistrationCountInterface {

  /**
   * Count the registrations.
   *
   * @return int
   *   The number of counted registrations.
   */
  public function countRegistrations(): int;

}
