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

}
