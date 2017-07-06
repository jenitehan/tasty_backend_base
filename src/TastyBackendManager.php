<?php

namespace Drupal\tasty_backend_base;

use Drupal\system\SystemManager;

/**
 * Tasty Backend Manager Service.
 */
class TastyBackendManager extends SystemManager {

  /**
   * Loads the contents of a menu block.
   *
   * Overridden from SystemManager to set the Tasty Backend 'Manage' menu.
   *
   * @return array
   *   A render array suitable for drupal_render.
   */
  public function getBlockContents() {
    $link = $this->menuActiveTrail->getActiveLink('tb-manage');
    if ($link && $content = $this->getAdminBlock($link)) {
      $output = [
        '#theme' => 'admin_block_content',
        '#content' => $content,
      ];
    }
    else {
      $output = [
        '#markup' => t('You do not have any administrative items.'),
      ];
    }
    return $output;
  }

  /**
   * Add a new administration view for a content type.
   *
   * @param Drupal\node\Entity\NodeType $type
   *    Drupal NodeType object.
   */
  public static function createAdminView($type) {

    // Default view doesn't have any type set.
    $type_filter = [
      'id' => 'type',
      'table' => 'node_field_data',
      'field' => 'type',
      'value' => [
        $type->id() => $type->id(),
      ],
      'entity_type' => 'node',
      'entity_field' => 'type',
      'plugin_id' => 'bundle',
      'group' => 1,
    ];

    // Override config from default view to create a new view.
    $config = \Drupal::configFactory()->getEditable('views.view.tb_manage_content');
    $config->setName('views.view.tb_manage_content_' . $type->id());
    $config->set('label', 'Tasty Backend Manage ' . $type->label());
    $config->set('id', 'tb_manage_content_' . $type->id());
    $config->set('status', TRUE);
    $config->set('description', 'Tasty Backend administration view to manage all ' . $type->label() . ' content');
    $config->set('display.default.display_options.access.options.perm', 'edit any ' . $type->id() . ' content');
    $config->set('display.default.display_options.filters.type', $type_filter);
    $config->set('display.default.display_options.title', 'Manage ' . $type->label() . ' content');
    $config->set('display.page_1.display_options.path', 'admin/manage/content/' . $type->id());
    $config->set('display.page_1.display_options.menu.title', $type->label());
    $config->set('display.page_1.display_options.menu.description', 'Manage ' . $type->label() . ' content.');
    $config->save(TRUE);
  }

}
