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

}
