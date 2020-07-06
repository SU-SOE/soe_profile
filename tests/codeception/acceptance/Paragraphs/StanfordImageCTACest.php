<?php

/**
 * Codeception tests on Image CTA paragraph type.
 */
class StanfordImageCTACest {

  /**
   * Create a CTA List paragraph to test.
   */
  protected function createParagraphs(\AcceptanceTester $I) {
    $paragraphs = [];
    $paragraphs[0] = $I->createEntity([
      'type' => 'stanford_image_cta',
      'stanford_image_cta_image' => [
        'target_id' => '4',
      ],
      'stanford_image_cta_link' => [
        'uri' => 'http://google.com',
        'title' => 'Link Alpha',
      ],
    ], 'paragraph');

    $paragraphs[1] = $I->createEntity([
      'type' => 'stanford_image_cta',
      'stanford_image_cta_image' => [
        'target_id' => '4',
      ],
      'stanford_image_cta_link' => [
        'uri' => '<front>',
        'title' => 'Internal Link test',
      ],
    ], 'paragraph');

    return $paragraphs;
  }

  /**
   * Create a node to hold the paragraph.
   */
  protected function createNodeWithParagraph(\AcceptanceTester $I) {
    $paragraph = $this->createParagraphs($I);
    $node = $I->createEntity([
      'type' => 'stanford_page',
      'title' => 'Test Image CTA',
      'su_page_components' => [
        'target_id' => $paragraphs[0]->id(),
        'entity' => $paragraph[0],
        'settings' => json_encode([
          'row' => 0,
          'index' => 0,
          'width' => 12,
          'admin_title' => 'Image CTA',
        ]),
        'target_id' => $paragraphs[1]->id(),
        'entity' => $paragraph[1],
        'settings' => json_encode([
          'row' => 0,
          'index' => 0,
          'width' => 12,
          'admin_title' => 'Image CTA - internal link',
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
    $I->seeElement("//div[contains(@class, 'su-image-cta-paragraph__image')]");
    $I->seeLink('Link Alpha', 'http://google.com');
    $I->see('Internal Link test');
    $I->click('Internal Link test');
    $I->amOnPage('/');
  }

}
