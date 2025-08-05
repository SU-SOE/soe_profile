<?php

use Faker\Factory;

/**
 * Codeception tests on CTA List paragraph type.
 *
 * @group stanford_cta_list
 */
class StanfordCTAListCest {

  /**
   * The Faker instance.
   *
   * @var \Faker\Generator
   */
  protected $faker;

  /**
   * Constructor.
   */
  public function __construct() {
    $this->faker = Factory::create();
  }

  /**
   * Create a CTA List paragraph to test.
   */
  protected function createParagraph(AcceptanceTester $I) {
    $paragraph = $I->createEntity([
      'type' => 'stanford_cta_list',
      'stanford_cta_list_header' => [
        'value' => 'Lorem Ipsum CTA List',
      ],
      'stanford_cta_list_deck' => [
        'value' => 'Test value for the deck',
      ],
      'stanford_cta_list_links' => [
        [
          'uri' => 'http://google.com',
          'title' => 'Link Alpha',
        ],
        [
          'uri' => 'http://google.com',
          'title' => 'Link Beta',
        ],
        [
          'uri' => 'http://google.com',
          'title' => 'Link Gamma',
        ],
      ],
    ], 'paragraph');
    return $paragraph;
  }

  /**
   * Create a node to hold the paragraph.
   */
  protected function createNodeWithParagraph(AcceptanceTester $I) {
    $paragraph = $this->createParagraph($I);

    $node = $I->createEntity([
      'type' => 'stanford_page',
      'title' => $this->faker->words(3, TRUE),
      'su_page_components' => [
        'target_id' => $paragraph->id(),
        'entity' => $paragraph,
      ],
    ]);
    return $node;
  }

  /**
   * Test the CTA List paragraph in the page.
   */
  public function testCtaList(AcceptanceTester $I) {
    $node = $this->createNodeWithParagraph($I);
    $I->amOnPage($node->toUrl()->toString());
    $I->canSee('Lorem Ipsum CTA List');
    $I->canSee('Test value for the deck');
    $I->canSeeLink('Link Alpha', 'http://google.com');
    $I->canSeeLink('Link Beta', 'http://google.com');
    $I->canSeeLink('Link Gamma', 'http://google.com');
  }

  /**
   * Test CTA List border removal functionality.
   */
  public function testCtaListBorderRemoval(AcceptanceTester $I) {
    // Test with border explicitly disabled using setBehaviorSettings method
    $paragraph_without_border = $I->createEntity([
      'type' => 'stanford_cta_list',
      'stanford_cta_list_header' => [
        'value' => 'CTA without Border',
      ],
      'stanford_cta_list_links' => [
        [
          'uri' => 'http://example.com',
          'title' => 'No Border Link',
        ],
      ],
    ], 'paragraph');

    // Set behavior settings after entity creation (checkbox checked = remove border)
    $paragraph_without_border->setBehaviorSettings('su_cta_list_styles', ['top_border' => TRUE]);
    $paragraph_without_border->save();

    $node_without_border = $I->createEntity([
      'type' => 'stanford_page',
      'title' => 'CTA No Border Test',
      'su_page_components' => [
        'target_id' => $paragraph_without_border->id(),
        'entity' => $paragraph_without_border,
      ],
    ]);

    $I->amOnPage($node_without_border->toUrl()->toString());
    $I->canSee('CTA without Border');

    // Check that the without-border class is applied
    $I->seeElement('.su-cta-list--without-border');
  }

}
