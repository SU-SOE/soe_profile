<?php

namespace Drupal\Tests\soe_paragraph_cta_list\Unit\Plugin\paragraphs\Behavior;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Form\FormState;
use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\soe_paragraph_cta_list\Plugin\paragraphs\Behavior\CtaListBehavior;
use Drupal\Tests\UnitTestCase;

/**
 * Tests the CTA List Behavior plugin.
 *
 * @group soe_paragraph_cta_list
 * @coversDefaultClass \Drupal\soe_paragraph_cta_list\Plugin\paragraphs\Behavior\CtaListBehavior
 */
class CtaListBehaviorTest extends UnitTestCase {

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $container = new ContainerBuilder();
    $container->set('string_translation', $this->getStringTranslationStub());
    \Drupal::setContainer($container);
  }

  /**
   * Test behavior functionality.
   */
  public function testCtaListBehavior() {
    // Test isApplicable() with non-matching paragraph type.
    $paragraph_type = $this->createMock(ParagraphsType::class);
    $paragraph_type->method('id')->willReturn($this->randomMachineName());
    $this->assertFalse(CtaListBehavior::isApplicable($paragraph_type));

    // Test isApplicable() with matching paragraph type.
    $paragraph_type = $this->createMock(ParagraphsType::class);
    $paragraph_type->method('id')->willReturn('stanford_cta_list');
    $this->assertTrue(CtaListBehavior::isApplicable($paragraph_type));

    // Create behavior instance.
    $field_manager = $this->createMock(EntityFieldManagerInterface::class);
    $behavior = new CtaListBehavior([], '', [], $field_manager);

    // Test default configuration.
    $default_config = $behavior->defaultConfiguration();
    $this->assertTrue($default_config['top_border']);

    // Test form build with default value.
    $paragraph = $this->createMock(ParagraphInterface::class);
    $paragraph->method('getBehaviorSetting')
      ->with('su_cta_list_styles', 'top_border', TRUE)
      ->willReturn(TRUE);

    $form = [];
    $form_state = new FormState();
    $element = $behavior->buildBehaviorForm($paragraph, $form, $form_state);

    $this->assertTrue($element['top_border']['#default_value']);
    $this->assertEquals('Gray line above content', (string) $element['top_border']['#title']);
    $this->assertEquals('checkbox', $element['top_border']['#type']);

    // Test form build with non-default value.
    $paragraph = $this->createMock(ParagraphInterface::class);
    $paragraph->method('getBehaviorSetting')
      ->with('su_cta_list_styles', 'top_border', TRUE)
      ->willReturn(FALSE);

    $element = $behavior->buildBehaviorForm($paragraph, $form, $form_state);
    $this->assertFalse($element['top_border']['#default_value']);

    // Test view() with border enabled (default).
    $display = $this->createMock(EntityViewDisplayInterface::class);
    $build = ['#attributes' => ['class' => []]];

    $paragraph = $this->createMock(ParagraphInterface::class);
    $paragraph->method('getBehaviorSetting')
      ->with('su_cta_list_styles', 'top_border', TRUE)
      ->willReturn(TRUE);

    $behavior->view($build, $paragraph, $display, 'default');
    $this->assertNotContains('su-cta-list--without-border', $build['#attributes']['class']);

    // Test view() with border disabled.
    $build = ['#attributes' => ['class' => []]];

    $paragraph = $this->createMock(ParagraphInterface::class);
    $paragraph->method('getBehaviorSetting')
      ->with('su_cta_list_styles', 'top_border', TRUE)
      ->willReturn(FALSE);

    $behavior->view($build, $paragraph, $display, 'default');
    $this->assertContains('su-cta-list--without-border', $build['#attributes']['class']);

  }

}
