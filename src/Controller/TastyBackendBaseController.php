<?php

namespace Drupal\tasty_backend_base\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\system\SystemManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TastyBackendBaseController.
 */
class TastyBackendBaseController extends ControllerBase {
  
  /**
   * System Manager Service.
   *
   * @var \Drupal\system\SystemManager
   */
  protected $systemManager;
  
  /**
   * Constructs a new TastyBackendBaseController.
   *
   * @param \Drupal\system\SystemManager $systemManager
   *   System manager service.
   */
  public function __construct(SystemManager $systemManager) {
    $this->systemManager = $systemManager;
  }
  
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('system.manager')
    );
  }

  /**
   * Page to list all content management views.
   *
   * @return array
   *   A render array suitable for drupal_render.
   */
  public function manageContentPage() {
    $this->menuLinkManager = \Drupal::service('plugin.manager.menu.link');
    $menu_links = $this->menuLinkManager->loadLinksByRoute('tasty_backend_base.manage_content_page', [], 'tb-manage');
    $menu_link = reset($menu_links);
    if (!empty($menu_links)) {
      $output = [
        '#theme' => 'admin_block_content',
        '#content' => $this->systemManager->getAdminBlock($menu_link),
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
