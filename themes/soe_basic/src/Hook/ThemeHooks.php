<?php

declare(strict_types=1);

namespace Drupal\soe_basic\Hook;

use Drupal\Core\Extension\ThemeExtensionList;
use Drupal\Core\Extension\ThemeSettingsProvider;
use Drupal\Core\Hook\Attribute\Hook;

class ThemeHooks {

  public function __construct(
    protected ThemeSettingsProvider $themeSettingsProvider,
    protected ThemeExtensionList $themeExtensionList
  ) {}

  /**
   * Prepares variables for the html.html.twig template.
   */
  #[Hook('preprocess_html')]
  public function preprocessHtml(&$variables): void {
    $variables['stanford_basic_path'] = $this->themeExtensionList->getPath('stanford_basic');
  }

  /**
   * Implements hook_theme_suggestions_HOOK_alter().
   */
  #[Hook('theme_suggestions_block_alter')]
  public function themeSuggestionsBlockAlter(array &$suggestions, array $variables): void {
    if (!empty($variables['elements']['#id']) && $variables['elements']['#id'] == 'soe_basic_search') {
      $suggestions[] = 'block__stanford_basic_search';
    }
  }
  /**
   * Implements hook_preprocess_HOOK().
   */
  #[Hook('preprocess_config_pages__stanford_local_footer')]
  public function preprocessLocalFooter(&$variables): void {
    $soe_basic_path = base_path() . $this->themeExtensionList->getPath('soe_basic');
    $variables['med_logo'] = "$soe_basic_path/src/assets/img/stanford_medicine.png";
    $variables['use_med_logo'] = $this->themeSettingsProvider->getSetting('show_su_med_logo');
  }

}
