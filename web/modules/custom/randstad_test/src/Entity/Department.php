<?php

namespace Drupal\randstad_test\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Departments config entity.
 *
 * @ConfigEntityType(
 *   id = "department",
 *   label = @Translation("Department"),
 *   handlers = {
 *     "list_builder" = "Drupal\randstad_test\DepartmentListBuilder",
 *     "form" = {
 *       "add" = "Drupal\randstad_test\Form\DepartmentForm",
 *       "edit" = "Drupal\randstad_test\Form\DepartmentForm",
 *       "delete" = "Drupal\randstad_test\Form\DepartmentDeleteForm",
 *     }
 *   },
 *   config_prefix = "randstad_test",
 *   admin_permission = "manage event registrations",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/department/{department}",
 *     "delete-form" = "/admin/config/department/{department}/delete",
 *   }
 * )
 */
class Department extends ConfigEntityBase implements DepartmentInterface {

  /**
   * The Department ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Department label.
   *
   * @var string
   */
  protected $label;

}
