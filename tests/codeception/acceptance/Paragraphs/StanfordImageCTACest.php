<?php

/**
 * Codeception tests on Image CTA paragraph type.
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

    $row = $I->createEntity([
      'type' => 'node_stanford_page_row',
      'su_page_components' => [
        'target_id' => $paragraph->id(),
        'entity' => $paragraph,
      ],
    ], 'paragraph_row');

    $node = $I->createEntity([
      'type' => 'stanford_page',
      'title' => 'Test Image CTA',
      'su_page_components' => [
        'target_id' => $row->id(),
        'entity' => $row,
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
    $I->seeElement("//div[contains(@class, 'su-image-cta-paragraph__image')]");
    $I->seeLink('Link Alpha', 'http://google.com');
  }

}
