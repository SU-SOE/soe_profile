<?php

/**
 * Codeception tests on Image CTA paragraph type.
 */
use Codeception\Util\Locator;

/**
 *
 */
class StanfordImageCTACest {

  /**
   * Create a CTA List paragraph to test.
   */
  protected function createParagraph(\AcceptanceTester $I) {
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
  protected function createNodeWithParagraph(\AcceptanceTester $I) {
    $paragraph = $this->createParagraph($I);
    $node = $I->createEntity([
      'type' => 'stanford_page',
      'title' => 'Test Image CTA',
      'su_page_components' => [
        'target_id' => $paragraph->id(),
        'entity' => $paragraph,
        'settings' => json_encode([
          'row' => 0,
          'index' => 0,
          'width' => 12,
          'admin_title' => 'Image CTA',
        ]),
      ],
    ]);
    // Clear router and menu cache so that the node urls work.
    $I->runDrush('cache-clear router');
    return $node;
  }

  /**
   * Test the Image CTA paragraph in the page.
   */
  public function testImageCta(\AcceptanceTester $I) {
    $node = $this->createNodeWithParagraph($I);
    $I->amOnPage($node->toUrl()->toString());
    $I->seeElement('div', ['class' => 'su-image-cta-paragraph__image']);
    $I->seeInSource('banner-151017-3191.jpg');
    // $I->seeElement("//div[@class='su-image-cta-paragraph__image']//img[contains(@src, '.jpg')]");
    $I->seeLink('Link Alpha', 'http://google.com');
  }

}
