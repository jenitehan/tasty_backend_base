<?php

namespace Drupal\tasty_backend_base\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\tasty_backend_base\TastyBackendManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TastyBackendBaseController.
 */
class TastyBackendBaseController extends ControllerBase {
  
  /**
   * Tasty Backend Manager Service.
   *
   * @var \Drupal\tasty_backend_base\manager\TastyBackendManager
   */
  protected $tastyBackendManager;
  
  /**
   * Constructs a new TastyBackendBaseController.
   *
   * @param \Drupal\tasty_backend_base\manager\TastyBackendManager $tastyBackendManager
   *   Tasty Backend Manager service.
   */
  public function __construct(TastyBackendManager $tastyBackendManager) {
    $this->tastyBackendManager = $tastyBackendManager;
  }
  
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tasty_backend_base.manager')
    );
  }

  /**
   * Page to list all content management views.
   *
   * @return array
   *   A render array suitable for drupal_render.
   */
  public function manageContentPage() {
    return $this->tastyBackendManager->getBlockContents();
  }

}
