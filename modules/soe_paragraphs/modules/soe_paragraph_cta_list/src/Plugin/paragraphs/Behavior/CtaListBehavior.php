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
      'top_border' => FALSE,
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state): array {
    $element = parent::buildBehaviorForm($paragraph, $form, $form_state);
    $element['top_border'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Remove Gray Line above content'),
      '#default_value' => (bool) $paragraph->getBehaviorSetting('su_cta_list_styles', 'top_border', FALSE),
    ];
    return $element;
  }

  /**
   * {@inheritDoc}
   */
  public function view(array &$build, ParagraphInterface $paragraph, EntityViewDisplayInterface $display, $view_mode): void {

    // Apply top border styling if removal is enabled
    $remove_top_border = $paragraph->getBehaviorSetting('su_cta_list_styles', 'top_border', FALSE);
    if ($remove_top_border) {
      $build['#attributes']['class'][] = 'su-cta-list--without-border';
    }
  }

}
