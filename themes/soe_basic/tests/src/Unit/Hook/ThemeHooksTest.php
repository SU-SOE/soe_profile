<?php

declare(strict_types=1);

namespace Drupal\Tests\soe_basic\Unit\Hook;

use Drupal\Core\Extension\ThemeExtensionList;
use Drupal\Core\Extension\ThemeSettingsProvider;
use Drupal\soe_basic\Hook\ThemeHooks;
use Drupal\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

/**
 * Unit tests for ThemeHooks.
 */
#[Group('soe_basic')]
#[CoversClass(ThemeHooks::class)]
class ThemeHooksTest extends UnitTestCase {

  /**
   * The hook class under test.
   *
   * @var \Drupal\soe_basic\Hook\ThemeHooks
   */
  protected ThemeHooks $hooks;

  /**
   * Mocked theme settings provider.
   *
   * @var \Drupal\Core\Extension\ThemeSettingsProvider
   */
  protected ThemeSettingsProvider $themeSettingsProvider;

  /**
   * Mocked theme extension list.
   *
   * @var \Drupal\Core\Extension\ThemeExtensionList
   */
  protected ThemeExtensionList $themeExtensionList;

  /**
   * {@inheritDoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->themeSettingsProvider = $this->createMock(ThemeSettingsProvider::class);
    $this->themeExtensionList = $this->createMock(ThemeExtensionList::class);
    $this->hooks = new ThemeHooks($this->themeSettingsProvider, $this->themeExtensionList);
  }

  /**
   * preprocessHtml() adds the path to the stanford_basic theme.
   */
  public function testPreprocessHtmlAddsStanfordBasicPath(): void {
    $this->themeExtensionList->method('getPath')
      ->with('stanford_basic')
      ->willReturn('themes/contrib/stanford_basic');

    $variables = [];
    $this->hooks->preprocessHtml($variables);

    $this->assertSame('themes/contrib/stanford_basic', $variables['stanford_basic_path']);
  }

  /**
   * The block__stanford_basic_search suggestion is added when the block
   * element id matches soe_basic_search.
   */
  public function testThemeSuggestionsBlockAlterAddsSuggestionForSearchBlock(): void {
    $suggestions = [];
    $variables = [
      'elements' => ['#id' => 'soe_basic_search'],
    ];

    $this->hooks->themeSuggestionsBlockAlter($suggestions, $variables);

    $this->assertContains('block__stanford_basic_search', $suggestions);
  }

  /**
   * Blocks with a different id are left untouched.
   */
  public function testThemeSuggestionsBlockAlterIgnoresOtherBlockIds(): void {
    $suggestions = [];
    $variables = [
      'elements' => ['#id' => 'some_other_block'],
    ];

    $this->hooks->themeSuggestionsBlockAlter($suggestions, $variables);

    $this->assertSame([], $suggestions);
  }

  /**
   * Blocks with no #id set at all are also left untouched.
   */
  public function testThemeSuggestionsBlockAlterIgnoresMissingElementId(): void {
    $suggestions = [];
    $variables = ['elements' => []];

    $this->hooks->themeSuggestionsBlockAlter($suggestions, $variables);

    $this->assertSame([], $suggestions);
  }

  /**
   * A completely empty $variables array (no 'elements' key at all) does not
   * cause any errors and leaves suggestions untouched.
   */
  public function testThemeSuggestionsBlockAlterIgnoresMissingElementsKey(): void {
    $suggestions = [];
    $variables = [];

    $this->hooks->themeSuggestionsBlockAlter($suggestions, $variables);

    $this->assertSame([], $suggestions);
  }

}
