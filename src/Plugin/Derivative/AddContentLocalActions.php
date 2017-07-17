<?php

/**
 * @file
 * Contains \Drupal\tasty_backend_base\Plugin\Derivative\AddContentLocalActions.
 */

namespace Drupal\tasty_backend_base\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;

/**
 * Defines node/add/{type} local tasks for each content type.
 */
class AddContentLocalActions extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $content_types = \Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple();    
    foreach ($content_types as $content_type) {
      $this->derivatives['tasty_backend_base.node_add_' . $content_type->id()] = $base_plugin_definition;
      $this->derivatives['tasty_backend_base.node_add_' . $content_type->id()]['title'] = 'Add ' . strtolower($content_type->label());
      $this->derivatives['tasty_backend_base.node_add_' . $content_type->id()]['route_name'] = 'node.add';
      $this->derivatives['tasty_backend_base.node_add_' . $content_type->id()]['route_parameters']['node_type'] = $content_type->id();
      $this->derivatives['tasty_backend_base.node_add_' . $content_type->id()]['appears_on'][] = 'view.tb_manage_content_' . $content_type->id() . '.page_1';
    }
    
    return $this->derivatives;
  }

}
