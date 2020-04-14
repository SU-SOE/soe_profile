<?php

namespace Drupal\soe_profile;

use Drupal\Core\Plugin\PluginBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Base class for install task plugins.
 *
 * @package Drupal\soe_profile
 */
abstract class InstallTaskBase extends PluginBase implements InstallTaskInterface {

  use StringTranslationTrait;

  /**
   * Entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

}
