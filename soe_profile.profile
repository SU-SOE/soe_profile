<?php

/**
 * @file
 * soe_profile.profile
 */

/**
 * Implements hook_install_tasks().
 *
 * This must remain procedural — Drupal core's
 * HookCollectorPass::checkForProceduralOnlyHooks() explicitly denies OOP
 * #[Hook] attribute support for install_tasks. The task callback resolves a
 * dependency-injected instance of InstallHooks via the class resolver so
 * InstallHooks::finalTask() can use constructor-injected services instead of
 * static \Drupal::service() calls.
 */
function soe_profile_install_tasks(&$install_state) {
  return ['soe_profile_final_task' => []];
}

/**
 * Perform final tasks after the profile has completed installing.
 *
 * @param array $install_state
 *   Current install state.
 */
function soe_profile_final_task(array &$install_state) {
  \Drupal::service('plugin.manager.install_tasks')->runTasks($install_state);
}

/**
 * Implements hook_ENTITY_TYPE_presave().
 */
function soe_profile_config_pages_presave(ConfigPagesInterface $config_page) {
  // During install, rebuild the router when saving a config page. This prevents
  // an error if the config page route doesn't exist for it yet. Event
  // subscriber doesn't work for this since it's during installation.
  if (InstallerKernel::installationAttempted()) {
    \Drupal::service('router.builder')->rebuild();
  }
}
