<?php
/**
 * @file
 * soe_profile.profile
 * Enables modules and site configuration for a standard site installation.
 */

use Drupal\menu_link_content\MenuLinkContentInterface;
use Drupal\block\Entity\Block;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;

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
  // Delete duplicate blocks.
  if ($block_config = Block::load('stanford_basic_main_menu')) {
    $block_config->delete();
  }
  if ($block_config = Block::load('stanford_basic_footer_menu')) {
    $block_config->delete();
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

/**
 * Implements hook_ENTITY_TYPE_view().
 */
function soe_profile_paragraph_view(array &$build, EntityInterface $entity, EntityViewDisplayInterface $display, $view_mode) {
  if ($entity->bundle() == 'soe_text_image_cta') {
    $build['#attached']['library'][] = 'soe_profile/text_cta_cards';
  }
}
