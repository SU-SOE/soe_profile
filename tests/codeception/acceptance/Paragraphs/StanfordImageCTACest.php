<?php

/**
 * Codeception tests on Image CTA paragraph type.
 */
class StanfordImageCTACest {


  protected $paragraphs;

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
        'uri' => '',
        'title' => 'Internal Link test',
      ],
    ], 'paragraph');

    $this->paragraphs = $paragraphs;
  }

  /**
   * Create a node to hold the paragraph.
   */
  protected function createNodeWithParagraph(\AcceptanceTester $I) {
    $nodes = [];
    $paragraph = $this->createParagraphs($I);
    $nodes[0] = $I->createEntity([
      'type' => 'stanford_page',
      'title' => 'Test Image CTA',
      'su_page_components' => [
        'target_id' => $this->paragraphs[0]->id(),
        'entity' => $this->paragraphs[0],
        'settings' => json_encode([
          'row' => 0,
          'index' => 0,
          'width' => 12,
          'admin_title' => 'Image CTA',
        ]),
      ],
    ]);

    $nodes[0] = $I->createEntity([
      'type' => 'stanford_page',
      'title' => 'Test Image CTA',
      'su_page_components' => [
        'target_id' => $this->paragraphs[1]->id(),
        'entity' => $this->paragraphs[1],
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
    return $nodes;
  }

  /**
   * Test the Image CTA paragraph in the page.
   */
  public function testImageCta(\AcceptanceTester $I) {
    $nodes = $this->createNodeWithParagraph($I);
    $I->amOnPage($nodes[0]->toUrl()->toString());
    $I->seeElement("//div[contains(@class, 'su-image-cta-paragraph__image')]");
    $I->seeLink('Link Alpha', 'http://google.com');
    $I->see('Internal Link test');

    $I->logInWithRole('administrator');
    $I->amOnPage($nodes[1]->toUrl()->toString());
    $I->click('.edit .tabs__tab a');
    $I->see('Image CTA - internal link');
    $I->click("//div[@id='react-su-page-components']//button[@class='button']");
    $I->see('The image to display');
    $I->fillField("//input[contains(@id, 'stanford_image_cta_link-uri-0')]", '<front>');
    $I->click('Continue');
    $I->click('edit-submit');
    $I->amOnPage($nodes[1]->toUrl()->toString());
    $I->see('Internal Link test');
    $I->click('Internal Link test');
    $I->amOnPage('/');
  }

}
