<?php

/**
 * @file
 * Soe_profile.post_update.php.
 */

use Drupal\Core\Site\Settings;
use Drupal\Core\Config\FileStorage;
use Drupal\config_pages\Entity\ConfigPages;

/**
 * Updates to config ignore before import.
 */
function soe_profile_post_update_8101() {
  $config_name = "config_ignore.settings";
  $config_path = Settings::get('config_sync_directory');
  $source = new FileStorage($config_path);
  $config_storage = \Drupal::service('config.storage');
  $config_storage->write($config_name, $source->read($config_name));
}

/**
 * Local footer lockup update options.
 */
function soe_profile_post_update_8102() {
  // Load and save the config_pages entity in order to set the default values.
  $config_page_machine_name = "stanford_local_footer";
  $entity = ConfigPages::config($config_page_machine_name);
  $entity->set('su_local_foot_use_loc', 1);
  $entity->set('su_local_foot_use_logo', 1);
  $entity->set('su_local_foot_loc_op', 'a');
  $entity->save();
}
