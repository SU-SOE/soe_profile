<?php

use Faker\Factory;

/**
 * Codeception tests on CTA List paragraph type.
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

}
