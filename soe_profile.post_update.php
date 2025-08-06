<?php

/**
 * @file
 * soe_profile.install
 */

/**
 * Implements hook_removed_post_updates().
 */
function soe_profile_removed_post_updates() {
  return [
    'soe_profile_post_update_8101' => '8.x-2.0',
    'soe_profile_post_update_8102' => '8.x-2.0',
    'soe_profile_post_update_8103' => '8.x-2.0',
    'soe_profile_post_update_8104' => '8.x-2.0',
    'soe_profile_post_update_8200_uuids' => '8.x-2.10',
    'soe_profile_post_update_8201_search' => '12.0.0',
    'soe_profile_post_update_8202' => '12.0.0',
    'soe_profile_post_update_update_field_defs' => '12.0.0',
    'soe_profile_post_update_samlauth' => '12.0.0',
    'soe_profile_post_update_site_orgs' => '12.0.0',
    'soe_profile_post_update_header_links_block' => '12.0.0',
    'soe_profile_post_update_unpublished_site_banner' => '12.0.0',
  ];
}

/**
 * Implements hook_post_update_NAME().
 */
function soe_profile_post_update_rabbit_hole_block() {
  $theme = \Drupal::config('system.theme')->get('default');
  if (in_array($theme, ['stanford_basic', 'minimally_branded_subtheme', 'soe_basic'])) {
    return;
  }
  \Drupal::entityTypeManager()->getStorage('block')->create([
    'id' => "{$theme}_rabbit_hole_message",
    'theme' => $theme,
    'region' => 'content',
    'weight' => -10,
    'plugin' => 'rabbit_hole_message',
    'settings' => [
      'id' => 'rabbit_hole_message',
      'label' => 'Rabbit Hole Message',
      'label_display' => 0,
      'provider' => 'stanford_profile_helper',
      'context_mapping' => ['node' => '@node.node_route_context:node'],
    ],
    'visibility' => [
      'user_role' => [
        'id' => 'user_role',
        'negate' => TRUE,
        'context_mapping' => ['user' => '@user.current_user_context:current_user'],
        'roles' => ['anonymous' => 'anonymous'],
      ],
    ],
  ])->save();
}
