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
    $markup = '<p>' . $this->t('<strong>Be careful!</strong> This will wipe out any customisations made to your content type administration views, and re-create them based on the Tasty Backend Manage Content view.') . '</p>';
    $markup .= '<p>' . $this->t('Any customisations made to the Tasty Backend Manage Content view will be applied to all selected views.') . '</p>';
    $form['intro'] =[
      '#markup' => $markup,
    ];
    $options = [];
    foreach (TastyBackendManager::loadContentManageViews() as $view) {
      $options[$view->id()] = $view->label();
    }
    $form['tasty_backend_views'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Select views to reload:'),
      '#description' => $this->t('All selected views will be reloaded from the default Tasty Backend Manage Content view.'),
      '#options' => $options,
      '#default_value' => array_keys($options),
    ];
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
    $views = $form_state->getValue('tasty_backend_views');
    foreach (\Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple() as $type) {
      $view_name = 'tb_manage_content_' . $type->id();
      if (isset($views[$view_name]) && $views[$view_name]) {
        TastyBackendManager::deleteAdminView($type->id());
        TastyBackendManager::addAdminView($type);
      }
    }
    drupal_set_message(t('The views have been reloaded.'));
  }

}
