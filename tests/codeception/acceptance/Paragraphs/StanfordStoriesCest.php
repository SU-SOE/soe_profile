<?php

/**
 * Codeception tests on Stories paragraph type.
 */
class StanfordStoriesCest {

  /**
   * Create a Stories paragraph to test.
   */
  protected function createParagraph(\AcceptanceTester $I) {
    $paragraph = $I->createEntity([
      'type' => 'stanford_stories',
      'stanford_stories_cta_link' => [
        'uri' => 'http://google.com',
        'title' => 'Link Alpha',
      ],
      'stanford_stories_node_link' => [
        'uri' => 'http://yahoo.com',
        'title' => 'Link Beta',
      ],
      'stanford_stories_name' => [
        'value' => 'Test value for the name',
      ],
      'stanford_stories_photo' => [
        'target_id' => '4',
      ],
      'stanford_stories_border' => [
        'value' => '1',
      ],
      'stanford_stories_quote' => [
        'value' => 'Test value for the quote',
      ],
      'stanford_stories_title' => [
        'value' => 'Test value for the title',
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
      'title' => 'Test Stories Paragraph',
      'su_page_components' => [
        'target_id' => $paragraph->id(),
        'entity' => $paragraph,
        'settings' => json_encode([
          'row' => 0,
          'index' => 0,
          'width' => 12,
          'admin_title' => 'Stories',
        ]),
      ],
    ]);
    // Clear router and menu cache so that the node urls work.
    $I->runDrush('cache-clear router');
    return $node;
  }

  /**
   * Test the CTA List paragraph in the page.
   */
  public function testStories(\AcceptanceTester $I) {
    $node = $this->createNodeWithParagraph($I);
    $I->amOnPage($node->toUrl()->toString());
    $I->seeElement('.border-color-1');
    $I->seeElement("//div[contains(@class, 'su-stories-paragraph__photo')]");
    $I->seeLink('Link Alpha', 'http://google.com');
    $I->seeLink('Link Beta', 'http://yahoo.com');
    $I->see('Test value for the name', '.su-stories-paragraph__name');
    $I->see('Test value for the quote', '.su-stories-paragraph__quote');
    $I->see('Test value for the title', '.su-stories-paragraph__title');
  }

}
