<?php

namespace Drupal\randstad_test\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\randstad_test\Form\RegistrationForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RegistrationController extends ControllerBase {

  /**
   * Construct the Registration controller.
   *
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder.
   */
  public function __construct(FormBuilderInterface $form_builder) {
    $this->formBuilder = $form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('form_builder')
    );
  }

  /**
   * Build the registration page.
   *
   * @return array
   *   A renderable array.
   */
  public function registrationPage($department) {
    $build = [];
    $build['registration_form'] = $this->formBuilder->getForm(RegistrationForm::class, $department->id());

    return $build;
  }

}
