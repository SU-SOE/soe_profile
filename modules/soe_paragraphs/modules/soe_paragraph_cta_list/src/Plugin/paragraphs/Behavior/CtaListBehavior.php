<?php

namespace Drupal\soe_paragraph_cta_list\Plugin\paragraphs\Behavior;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\paragraphs\ParagraphsBehaviorBase;

/**
 * Teaser paragraph behaviors.
 *
 * @ParagraphsBehavior(
 *   id = "su_cta_list_styles",
 *   label = @Translation("CTA List Styles"),
 *   description = @Translation("Style options for CTA list paragraph")
 * )
 */
class CtaListBehavior extends ParagraphsBehaviorBase {

  /**
   * {@inheritDoc}
   */
  public static function isApplicable(ParagraphsType $paragraphs_type): bool {
    return $paragraphs_type->id() == 'stanford_cta_list';
  }

  /**
   * {@inheritDoc}
   */
  public function defaultConfiguration(): array {
    return [
      'top_border' => TRUE,
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state): array {
    $element = parent::buildBehaviorForm($paragraph, $form, $form_state);
    $element['top_border'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Gray line above content'),
      '#default_value' => (bool) $paragraph->getBehaviorSetting('su_cta_list_styles', 'top_border', TRUE),
    ];
    return $element;
  }

  /**
   * {@inheritDoc}
   */
  protected function filterBehaviorFormSubmitValues(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state): array {
    // Get the form values for this behavior plugin
    $values = $form_state->getValues();
    
    // Instead of using NestedArray::filter() which removes falsy values,
    // we'll preserve checkbox values by only filtering out NULL and empty strings
    $filter_callback = function($value) {
      // Keep all values except NULL and empty strings
      // This preserves FALSE and 0 from unchecked checkboxes
      return $value !== NULL && $value !== '';
    };
    
    return \Drupal\Component\Utility\NestedArray::filter($values, $filter_callback);
  }

  /**
   * {@inheritDoc}
   */
  public function view(array &$build, ParagraphInterface $paragraph, EntityViewDisplayInterface $display, $view_mode): void {

    // Apply top border styling if enabled
    $top_border = $paragraph->getBehaviorSetting('su_cta_list_styles', 'top_border', TRUE);
    if (!$top_border) {
      $build['#attributes']['class'][] = 'su-cta-list--without-border';
    }
  }
  

}
