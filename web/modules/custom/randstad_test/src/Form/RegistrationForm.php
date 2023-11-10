<?php

namespace Drupal\randstad_test\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Registration form.
 */
class RegistrationForm extends FormBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * Constructs the registration form.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'randstad_registration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $department = NULL) {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name of the employee'),
      '#required' => TRUE,
    ];

    $form['one_plus'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('One plus'),
      '#options' => [
        'yes' => $this->t('YES'),
        'no' => $this->t('NO'),
      ],
      '#required' => TRUE,
    ];

    $form['kids_amount'] = [
      '#type' => 'number',
      '#title' => $this->t('Amount of kids'),
      '#required' => TRUE,
      '#min' => 0,
    ];


    $form['vegetarians_amount'] = [
      '#type' => 'number',
      '#title' => $this->t('Amount of vegetarians'),
      '#required' => TRUE,
      '#min' => 0,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email address'),
      '#required' => TRUE,
    ];

    $form['department'] = [
      '#type' => 'hidden',
      '#value' => $department,
    ];

    $form['actions'] = ['#type' => 'actions'];

    $form['actions']['register'] = [
      '#type' => 'submit',
      '#value' => $this->t('Register'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    $values = $form_state->getValues();

    $people = $values['kids_amount'] + 1 ?? 1;
    $vegetarians = $values['vegetarians_amount'] ?? 0;
    $email = $values['email'];

    if ($vegetarians > $people) {
      $form_state->setErrorByName('vegetarians_amount', $this->t('Amount of vegetarians can not be higher than the total amount of people.'));
    }

    if ($this->validateEmailAddress($email)) {
      $form_state->setErrorByName('email', $this->t('Email address is already registered.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $saved = $this->entityTypeManager->getStorage('node')->create([
      'type' => 'registration',
      'title' => $values['name'],
      'field_one_plus' => array_keys(array_filter($values['one_plus'])),
      'field_amount_of_kids' => $values['kids_amount'],
      'field_amount_of_vegetarians' => $values['vegetarians_amount'],
      'field_email_address' => $values['email'],
      'field_department' => $values['department'],
    ])->save();

    if ($saved) {
      \Drupal::messenger()->addMessage($this->t('Registration completed successfully!'));
    }
  }

  /**
   * Validate email address and ensure user won't register twice.
   *
   * @param string $email
   *   The email address to be validated.
   *
   * @return bool
   *   TRUE email is not registered, FALSE otherwise.
   */
  protected function validateEmailAddress(string $email): bool {
    $email_exist = $this->entityTypeManager
      ->getStorage('node')
      ->loadByProperties(['field_email_address' => $email]);

    return (bool) $email_exist;
  }

}
