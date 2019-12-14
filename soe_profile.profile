<?php
/**
 * @file
 * soe_profile.profile
 * Enables modules and site configuration for a standard site installation.
 */

use Drupal\menu_link_content\MenuLinkContentInterface;
use Drupal\block\Entity\Block;

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
  drupal_flush_all_caches();
  // Delete duplicate blocks.
  if ($block_config = Block::load('stanford_basic_main_menu')) {
    $block_config->delete();
  }
  if ($block_config = Block::load('stanford_basic_footer_menu')) {
    $block_config->delete();
  }
  \Drupal::service('theme_installer')->install(['stanford_basic']);
  $modules = [
    'layout_builder_restrictions',
    'stanford_news'
  ];
  foreach ($modules as $module) {
    \Drupal::service('module_installer')->install([$module]);
  }
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
