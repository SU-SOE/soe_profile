<?php
/**
 * @file
 * soe_profile.profile
 * Enables modules and site configuration for a standard site installation.
 */

use Drupal\menu_link_content\MenuLinkContentInterface;

/**
 * Implements hook_install_tasks().
 */
function soe_profile_install_tasks(&$install_state) {
  return ['soe_profile_final_task' => []];
}

function soe_profile_final_task(&$install_state) {

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
}
