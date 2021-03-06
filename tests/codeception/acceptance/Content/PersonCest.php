<?php

/**
 * Test the news functionality.
 */
class PersonCest {

  /**
   * Sidebar "Contact" header should only appear once.
   */
  public function testDoubleHeader(AcceptanceTester $I){
    $node = $I->createEntity([
      'type' => 'stanford_person',
      'title' => 'Foo Bar',
      'su_person_first_name' => 'Foo',
      'su_person_last_name' => 'Bar',
      'su_person_telephone' => '1234567890',
    ]);
    $I->amOnPage($node->toUrl()->toString());
    $I->canSee('Foo Bar', 'h1');
    $headers = $I->grabMultiple('h2');
    $contacts = 0;
    foreach ($headers as $header) {
      if (strpos(strtolower($header), 'contact') !== FALSE) {
        $contacts++;
      }
    }
    $I->assertEquals(1, $contacts);
  }

  /**
   * Test that the default content has installed and is unpublished.
   */
  public function testDefaultContentExists(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $I->amOnPage("/admin/content");
    $I->see("Haley Jackson");
    $I->amOnPage("/person/haley-jackson");
    $I->see("This page is currently unpublished and not visible to the public.");
    $I->see("Haley Jackson");
    $I->see("People", ".su-multi-menu");

  }

  /**
   * Test that the vocabulary and terms exist.
   */
  public function testVocabularyTermsExists(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $I->amOnPage("/admin/structure/taxonomy/manage/stanford_person_types/overview");
    $I->canSeeNumberOfElements(".term-id", 14);
  }

  /**
   * Test that the view pages exist.
   */
  public function testViewPagesExist(AcceptanceTester $I) {
    $I->amOnPage("/people");
    $I->see("Sorry, no results found");
    $I->seeLink('Student');
    $I->click("a[href='/people/staff']");
    $I->canSeeResponseCodeIs(200);
    $I->see("Sorry, no results found");
    $I->see("Filter By Person Type");
  }

  /**
   * Test that content that gets created has the right url, header, and shows
   * up in the all view.
   */
  public function testCreatePerson(AcceptanceTester $I) {
    $I->createEntity([
      'type' => 'stanford_person',
      'su_person_first_name' => "John",
      'su_person_last_name' => "Wick",
    ]);
    $I->amOnPage("/person/john-wick");
    $I->see("John Wick");
    $I->runDrush('cr');
    $I->amOnPage("/people");
    $I->see("John Wick");
  }

  /**
   * Test that the XML sitemap and metatag configuration is set.
   */
  public function testXMLMetaDataRevisions(AcceptanceTester $I) {
    $I->logInWithRole('administrator');

    // Revision Delete is enabled.
    $I->amOnPage('/admin/structure/types/manage/stanford_person');
    $I->seeCheckboxIsChecked("#edit-node-revision-delete-track");
    $I->seeCheckboxIsChecked("#edit-options-revision");
    $I->seeInField("#edit-minimum-revisions-to-keep", 5);

    // XML Sitemap.
    $I->amOnPage("/admin/config/search/xmlsitemap/settings");
    $I->see("Person");
    $I->amOnPage("/admin/config/search/xmlsitemap/settings/node/stanford_person");
    $I->selectOption("#edit-xmlsitemap-status", 1);

    // Metatags.
    $I->amOnPage("/admin/config/search/metatag/page_variant__people-layout_builder-0");
    $I->canSeeResponseCodeIs(200);
    $I->amOnPage("/admin/config/search/metatag/page_variant__stanford_person_list-layout_builder-1");
    $I->canSeeResponseCodeIs(200);
    $I->amOnPage("/admin/config/search/metatag/node__stanford_person");
    $I->canSeeResponseCodeIs(200);
  }

  /**
   * CAP-52: Check for the new fields.
   */
  public function testCap52Fields(AcceptanceTester $I) {
    $I->logInWithRole('administrator');

    $I->amOnPage('/admin/structure/types/manage/stanford_person/fields');
    $I->canSee('Academic Appointments');
    $I->canSee('Administrative Appointments');
    $I->canSee('Scholarly and Research Interests');

    $I->amOnPage('/admin/structure/types/manage/stanford_person/form-display');
    $I->canSeeOptionIsSelected('fields[su_person_academic_appt][region]', 'Disabled');
    $I->canSeeOptionIsSelected('fields[su_person_admin_appts][region]', 'Disabled');
    $I->canSeeOptionIsSelected('fields[su_person_scholarly_interests][region]', 'Disabled');
  }

  /**
   * D8CORE-2613: Taxonomy menu items don't respect the UI.
   */
  public function testD8Core2613Terms(AcceptanceTester $I) {
    $I->logInWithRole('site_manager');

    $foo = $I->createEntity([
      'name' => 'Foo',
      'vid' => 'stanford_person_types',
    ], 'taxonomy_term');
    $bar = $I->createEntity([
      'name' => 'Bar',
      'vid' => 'stanford_person_types',
    ], 'taxonomy_term');
    $baz = $I->createEntity([
      'name' => 'Baz',
      'vid' => 'stanford_person_types',
      'parent' => ['target_id' => $foo->id()],
    ], 'taxonomy_term');

    $I->amOnPage('/people');
    $I->canSeeLink('Foo');
    $I->canSeeLink('Bar');
    $I->cantSeeLink('Baz');

    $I->amOnPage($baz->toUrl('edit-form')->toString());
    $I->selectOption('Parent term', '<root>');
    $I->click('Save');

    $I->amOnPage('/people');
    $I->canSeeLink('Baz');

    $I->amOnPage($baz->toUrl('edit-form')->toString());
    $I->selectOption('Parent term', 'Bar');
    $I->click('Save');

    $I->amOnPage('/people');
    $I->cantSeeLink('Baz');
  }

  /**
   * Published checkbox should be hidden on term edit pages.
   */
  public function testTermPublishing(AcceptanceTester $I) {
    $I->logInWithRole('site_manager');
    $term = $I->createEntity([
      'vid' => 'stanford_person_types',
      'name' => 'Foo',
    ], 'taxonomy_term');
    $I->amOnPage($term->toUrl('edit')->toString());
    $I->cantSee('Published');
  }

}
