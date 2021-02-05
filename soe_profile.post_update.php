<?php

/**
 * @file
 * soe_profile.install
 */

use Drupal\Core\Site\Settings;
use Drupal\Core\Config\FileStorage;
use Drupal\react_paragraphs\Entity\ParagraphRow;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Implements hook_removed_post_updates().
 */
function soe_profile_removed_post_updates() {
  return [
    'soe_profile_post_update_8101' => '8.x-2.0',
    'soe_profile_post_update_8102' => '8.x-2.0',
    'soe_profile_post_update_8103' => '8.x-2.0',
    'soe_profile_post_update_8104' => '8.x-2.0',
  ];
}

/**
 * Set the UUID's on block configs.
 */
function soe_profile_post_update_8200_uuids() {
  $config_factory = \Drupal::configFactory();
  if ($config = $config_factory->getEditable('block.block.soe_basic_config_pages_global_msg')) {
    $config->delete();
  }
  if ($config = $config_factory->getEditable('block.block.soe_basic_config_pages_super_footer')) {
    $config->delete();
  }
}
