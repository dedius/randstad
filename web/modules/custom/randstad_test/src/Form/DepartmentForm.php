<?php

namespace Drupal\randstad_test\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Create or update form handler for Department config entity.
 */
class DepartmentForm extends EntityForm {

  /**
   * Constructs the AddDepartmentForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entityTypeManager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $department = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Department name'),
      '#maxlength' => 255,
      '#default_value' => $department->label(),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $department->id(),
      '#machine_name' => [
        'exists' => [$this, 'exist'],
      ],
      '#disabled' => !$department->isNew(),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $example = $this->entity;
    $status = $example->save();

    if ($status === SAVED_NEW) {
      $this->messenger()->addMessage($this->t('The %label Department created.', [
        '%label' => $example->label(),
      ]));
    }
    else {
      $this->messenger()->addMessage($this->t('The %label Department updated.', [
        '%label' => $example->label(),
      ]));
    }

    $form_state->setRedirect('entity.department.collection');
  }

  /**
   * Helper function to check if configuration entity exists.
   */
  public function exist($id) {
    $entity = $this->entityTypeManager->getStorage('department')
      ->getQuery()
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }

}
