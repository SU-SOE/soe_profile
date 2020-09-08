<?php

/**
 * @file
 * Soe_profile.post_update.php.
 */

use Drupal\Core\Site\Settings;
use Drupal\Core\Config\FileStorage;

/**
 * Updates to config ignore before import.
 */
function soe_profile_post_update_8101() {
  $config_name = "config_ignore.settings.yml";
  $config_path = Settings::get('config_sync_directory');
  $source = new FileStorage($config_path);
  $config_storage = \Drupal::service('config.storage');
  $config_storage->write($config_name, $source->read($config_name));
}
