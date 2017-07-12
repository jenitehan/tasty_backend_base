<?php

namespace Drupal\tasty_backend_base\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class TastyBackendSettingsForm.
 */
class TastyBackendSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'tasty_backend_base.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'tasty_backend_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('tasty_backend_base.settings');
    $form['apply_default_content_perms'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Apply default content type permissions to content admin role'),
      '#description' => $this->t('Will add relevant permissions to the content admin role for all new content types.'),
      '#default_value' => $config->get('apply_default_content_perms'),
    ];
    $form['apply_default_vocab_perms'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Apply default vocabulary permissions to content admin role'),
      '#description' => $this->t('Will add relevant permissions to the content admin role for all new taxonomy vocabularies.'),
      '#default_value' => $config->get('apply_default_vocab_perms'),
    ];
    return parent::buildForm($form, $form_state);
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
    parent::submitForm($form, $form_state);

    $this->config('tasty_backend_base.settings')
      ->set('apply_default_content_perms', $form_state->getValue('apply_default_content_perms'))
      ->set('apply_default_vocab_perms', $form_state->getValue('apply_default_vocab_perms'))
      ->save();
  }

}
