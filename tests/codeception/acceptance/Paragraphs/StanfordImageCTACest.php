<?php

use Faker\Factory;

/**
 * Codeception tests on Image CTA paragraph type.
 */
class StanfordImageCTACest {

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
      'type' => 'stanford_image_cta',
      'stanford_image_cta_image' => [
        'target_id' => '4',
      ],
      'stanford_image_cta_link' => [
        'uri' => 'http://google.com',
        'title' => 'Link Alpha',
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
   * Test the Image CTA paragraph in the page.
   */
  public function testImageCta(AcceptanceTester $I) {
    $node = $this->createNodeWithParagraph($I);
    $I->amOnPage($node->toUrl()->toString());
    $I->seeElement("//div[contains(@class, 'su-image-cta-paragraph__image')]");
    $I->seeLink('Link Alpha', 'http://google.com');
  }

}
