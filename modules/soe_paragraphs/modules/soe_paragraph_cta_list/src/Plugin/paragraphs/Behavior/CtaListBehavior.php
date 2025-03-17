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
      'top_border' => 'Yes',
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state): array {
    $element = parent::buildBehaviorForm($paragraph, $form, $form_state);
    // Let's do this with strings, in case we want to add more options later.
    $element['top_border'] = [
      '#type' => 'select',
      '#title' => $this->t('Gray line above content'),
      '#options' => [
        'Yes' => 'Yes',
        'No' => 'No',
      ],
      '#default_value' => (string) $paragraph->getBehaviorSetting('su_cta_list_styles', 'top_border'),
    ];
    return $element;
  }

  /**
   * {@inheritDoc}
   */
  public function view(array &$build, ParagraphInterface $paragraph, EntityViewDisplayInterface $display, $view_mode): void {

    // Apply top border styling if enabled
    $top_border = $paragraph->getBehaviorSetting('su_cta_list_styles', 'top_border', 'Yes');
    if ($top_border === 'No') {
      $build['#attributes']['class'][] = 'su-cta-list--without-border';
    }
  }
  

}
