<?php
/**
 * @file
 * soe_profile.profile
 * Enables modules and site configuration for a standard site installation.
 */

use Drupal\menu_link_content\MenuLinkContentInterface;
use Drupal\block\Entity\Block;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Access\AccessResult;

/**
 * Implements hook_install_tasks().
 */
function soe_profile_install_tasks(&$install_state) {
  return ['soe_profile_final_task' => []];
}

/**
 * Install task that executes at the end of the installation.
 *
 * @param array $install_state
 *   Current install state.
 *
 * @throws \Drupal\Core\Entity\EntityStorageException
 */
function soe_profile_final_task(array &$install_state) {
  \Drupal::service('plugin.manager.install_tasks')->runTasks($install_state);
}

/**
 * Implements hook_ENTITY_TYPE_insert().
 */
function soe_profile_menu_link_content_presave(MenuLinkContentInterface $entity) {
  // For new menu link items created on a node form (normally), set the expanded
  // attribute so all menu items are expanded by default.
  if ($entity->isNew()) {
    $entity->set('expanded', TRUE);
  }

  // When a menu item is added as a child of another menu item clear the parent
  // pages cache so that the block shows up as it doesn't get invalidated just
  // by the menu cache tags.
  $parent_id = $entity->getParentId();
  if (!empty($parent_id)) {
    list($entity_name, $uuid) = explode(':', $parent_id);
    $menu_link_content = \Drupal::entityTypeManager()->getStorage($entity_name)->loadByProperties(['uuid' => $uuid]);
    if (is_array($menu_link_content)) {
      $parent_item = array_pop($menu_link_content);
      $params = $parent_item->getUrlObject()->getRouteParameters();
      if (isset($params['node'])) {
        Cache::invalidateTags(['node:' . $params['node']]);
      }
    }
  }
}

/**
 * Implements hook_entity_field_access().
 */
function soe_profile_entity_field_access($operation, FieldDefinitionInterface $field_definition, AccountInterface $account, FieldItemListInterface $items = NULL) {
  if ($field_definition->getType() == 'entity_reference' && $field_definition->getSetting('handler') == 'layout_library') {
    $entity_type = $field_definition->getTargetEntityTypeId();
    $bundle = $field_definition->getTargetBundle();
    if (!$account->hasPermission("choose layout for $entity_type $bundle")) {
      return AccessResult::forbidden();
    }
  }
  return AccessResult::neutral();
}
