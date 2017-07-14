<?php

namespace Drupal\tasty_backend_base\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\tasty_backend_base\TastyBackendManager;

/**
 * Class TastyBackendReloadAdminViewsForm.
 */
class TastyBackendReloadAdminViewsForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'tasty_backend_reload_admin_views_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['intro'] =[
      '#markup' => '<p>' . $this->t('Be careful! This will wipe out any customisations made to your content type administration views, and re-create them based on the Tasty Backend Manage Content view.') . '</p>',
    ];
    // @todo: Show a list of views that will be affected. Or maybe even provide checkboxes to select which ones to delete.
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Reload views'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {    
    foreach (\Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple() as $type) {
      TastyBackendManager::deleteAdminView($type->id());
      TastyBackendManager::addAdminView($type);
    }
    drupal_set_message(t('The views have been reloaded.'));
  }

}
