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
  public static function addAdminView($type) {

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
    
    // Duplicate the view.
    $view = \Drupal\views\Views::getView('tb_manage_content')->storage->createDuplicate();
    
    // Set some basic info.
    $view->setStatus(TRUE);
    $view->set('id', 'tb_manage_content_' . $type->id());
    $view->set('label', 'Tasty Backend Manage ' . $type->label());
    $view->set('description', 'Tasty Backend administration view to manage all ' . $type->label() . ' content.');
    
    // Set the display options.
    $display = $view->get('display');
    $display['default']['display_options']['access']['options']['perm'] = 'edit any ' . $type->id() . ' content';
    $display['default']['display_options']['filters']['type'] = $type_filter;
    $display['default']['display_options']['title'] = 'Manage ' . $type->label() . ' content';
    $display['page_1']['display_options']['path'] = 'admin/manage/content/' . $type->id();
    $display['page_1']['display_options']['menu']['title'] = $type->label();
    $display['page_1']['display_options']['menu']['description'] = 'Manage ' . $type->label() . ' content.';
    $view->set('display', $display);
    
    // Save the new view.
    $view->save();
  }

  /**
   * Deletes an administration view for a content type.
   *
   * $content_type
   *    Machine name of content type.
   */
  public static function deleteAdminView($content_type) {
    $storage_handler = \Drupal::entityTypeManager()->getStorage('view');
    $entities = $storage_handler->loadMultiple(['tb_manage_content_' . $content_type]);
    $storage_handler->delete($entities);
  }

  /**
   * Add default permissions for a content type.
   *
   * @param Drupal\node\Entity\NodeType $type
   *    Drupal NodeType object.
   * @param $rid
   *    The ID of a user role to alter.
   */
  public static function addContentTypePermissions($type, $rid = 'content_admin') {
    $role = \Drupal\user\Entity\Role::load($rid);
    user_role_grant_permissions($rid, [
      'create ' . $type->id() . ' content',
      'delete any ' . $type->id() .  ' content',
      'edit any ' . $type->id() .  ' content',
      'override ' . $type->id() . ' published option',
      'view any unpublished ' . $type->id() . ' content',
    ]);
    $args = [
      '@role_name' => $role->label(),
      '@type' => $type->label(),
    ];
    drupal_set_message(t('Default content type permissions have been added to the @role_name role for the @type content type.', $args));
  }

  /**
   * Add default permissions for a taxonomy vocabulary.
   *
   * @param Drupal\taxonomy\Entity\Vocabulary $vocabulary
   *    Drupal Vocabulary object.
   * @param $rid
   *    The ID of a user role to alter.
   */
  public static function addVocabularyPermissions($vocabulary, $rid = 'content_admin') {
    $role = \Drupal\user\Entity\Role::load($rid);
    user_role_grant_permissions($rid, [
      'delete terms in ' . $vocabulary->id(),
      'edit terms in ' . $vocabulary->id(),
      'add terms in ' . $vocabulary->id(),
      'reorder terms in ' . $vocabulary->id(),
    ]);
    $args = [
      '@role_name' => $role->label(),
      '@vocabulary' => $vocabulary->label(),
    ];
    drupal_set_message(t('Default vocabulary permissions have been added to the @role_name role for the @vocabulary vocabulary.', $args));
  }

  /**
   * Load all Tasty Backend content management views.
   * @param $content_type
   *    Optional. Machine name of content type.
   * @return object or array
   *    An individual view object if $content_type is set, otherwise an array of all tasty backend content management views.
   */
  public static function loadContentManageViews($content_type = NULL) {
    $views = \Drupal::entityTypeManager()->getStorage('view')->loadMultiple();
    $content_types = \Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple();
    $tb_views = [];
    foreach ($content_types as $name => $type) {
      if (array_key_exists('tb_manage_content_' . $name, $views)) {
        $tb_views['tb_manage_content_' . $name] = $views['tb_manage_content_' . $name];
      }
    }
    if ($content_type) {
      return $tb_views['tb_manage_content_' . $content_type];
    }
    return $tb_views;
  }

}
