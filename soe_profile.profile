<?php

/**
 * @file
 * soe_profile.profile
 */

/**
 * Implements hook_install_tasks().
 */
function soe_profile_install_tasks(&$install_state) {
  return ['soe_profile_final_task' => []];
}
