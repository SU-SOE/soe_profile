<?php

/**
 * @file
 * Soe_profile.post_update.php.
 */

use Drupal\Core\Site\Settings;
use Drupal\Core\Config\FileStorage;
use Drupal\config_pages\Entity\ConfigPages;

/**
 * Imports a single config file.
 *
 * @param string $config_name
 *   The name of the config item. eg: 'config_ignore.settings'.
 */
function _soe_profile_import_config($config_name) {
  $config_path = Settings::get('config_sync_directory');
  $source = new FileStorage($config_path);
  $config_storage = \Drupal::service('config.storage');
  $config_storage->write($config_name, $source->read($config_name));
}

/**
 * Import field related config.
 *
 * @param array $configs
 *   An array of config file names.
 * @param string $type
 *   The entity type of the config to save.
 */
function _soe_profile_import_field_config(array $configs, $type) {
  // EM.
  $entity_manager = \Drupal::entityManager();
  $config_path = Settings::get('config_sync_directory');
  $source = new FileStorage($config_path);

  // Create the new field storages.
  foreach ($configs as $config_name) {
    $entity_manager->getStorage($type)
      ->createFromStorageRecord($source->read($config_name))
      ->enforceIsNew()
      ->save();
  }
}

/**
 * Updates to config ignore before import.
 */
function soe_profile_post_update_8101() {
  $config_name = "config_ignore.settings";
  _soe_profile_import_config($config_name);
}

/**
 * Local footer lockup update options.
 */
function soe_profile_post_update_8102() {
  // Update the config_pages bundle with the new fields first.
  $configs = [
    'field.storage.config_pages.su_local_foot_line_1',
    'field.storage.config_pages.su_local_foot_line_2',
    'field.storage.config_pages.su_local_foot_line_3',
    'field.storage.config_pages.su_local_foot_line_4',
    'field.storage.config_pages.su_local_foot_line_5',
    'field.storage.config_pages.su_local_foot_loc_img',
    'field.storage.config_pages.su_local_foot_loc_link',
    'field.storage.config_pages.su_local_foot_loc_op',
    'field.storage.config_pages.su_local_foot_pr_co',
    'field.storage.config_pages.su_local_foot_se_co',
    'field.storage.config_pages.su_local_foot_tr_co',
    'field.storage.config_pages.su_local_foot_use_loc',
    'field.storage.config_pages.su_local_foot_use_logo',
  ];

  _soe_profile_import_field_config($configs, 'field_storage_config');
}

/**
 * Local footer lockup update options part 2.
 */
function soe_profile_post_update_8103() {

  // Create the field configs.
  $configs = [
    'field.field.config_pages.stanford_local_footer.su_local_foot_line_1',
    'field.field.config_pages.stanford_local_footer.su_local_foot_line_2',
    'field.field.config_pages.stanford_local_footer.su_local_foot_line_3',
    'field.field.config_pages.stanford_local_footer.su_local_foot_line_4',
    'field.field.config_pages.stanford_local_footer.su_local_foot_line_5',
    'field.field.config_pages.stanford_local_footer.su_local_foot_loc_img',
    'field.field.config_pages.stanford_local_footer.su_local_foot_loc_link',
    'field.field.config_pages.stanford_local_footer.su_local_foot_loc_op',
    'field.field.config_pages.stanford_local_footer.su_local_foot_pr_co',
    'field.field.config_pages.stanford_local_footer.su_local_foot_se_co',
    'field.field.config_pages.stanford_local_footer.su_local_foot_tr_co',
    'field.field.config_pages.stanford_local_footer.su_local_foot_use_loc',
    'field.field.config_pages.stanford_local_footer.su_local_foot_use_logo',
  ];

  _soe_profile_import_field_config($configs, 'field_config');
}

/**
 * Local footer lockup update default options.
 */
function soe_profile_post_update_8104() {
  // Load and save the config_pages entity in order to set the default values.
  $entity = ConfigPages::config("stanford_local_footer");
  $entity->set('su_local_foot_use_loc', 1);
  $entity->set('su_local_foot_use_logo', 1);
  $entity->set('su_local_foot_loc_op', 'a');
  $entity->save();
}