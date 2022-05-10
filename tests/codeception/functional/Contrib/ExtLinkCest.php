<?php

use Drupal\config_pages\Entity\ConfigPages;

/**
 * Test the external link module functionality.
 */
class ExtLinkCest {

  /**
   * External link config settings.
   *
   * @var array
   */
  protected $extLinkSettings = [];

  /**
   * Start with a clean config page.
   * Modify the extlink settings first.
   *
   * @param \AcceptanceTester $I
   *   Tester.
   */
  public function _before(FunctionalTester $I){
    $this->_after($I);
    $config = \Drupal::configFactory()->getEditable('extlink.settings');
    $this->extLinkSettings = $config->getRawData();
    $config->set('extlink_class', 'su-link su-link--external')->save();
  }

  /**
   * Always cleanup the config after testing.
   *
   * @param \AcceptanceTester $I
   *   Tester.
   */
  public function _after(FunctionalTester $I) {
    if ($config_page = ConfigPages::load('stanford_local_footer')) {
      $config_page->delete();
    }

    $config_page = ConfigPages::create([
      'type' => 'stanford_local_footer',
      'su_local_foot_use_loc' => TRUE,
      'su_local_foot_use_logo' => TRUE,
      'su_local_foot_loc_op' => 'a',
      'context' => 'a:0:{}',
    ]);
    $config_page->save();

    if ($this->extLinkSettings) {
      \Drupal::configFactory()
        ->getEditable('extlink.settings')
        ->setData($this->extLinkSettings)
        ->save();
    }
  }

  /**
   * Test external links get the added class and svg.
   */
  public function testExtLink(FunctionalTester $I) {

    // Local footer block isnt showing up on circle for some reason.
    $I->logInWithRole('site_manager');
    $I->amOnPage('/admin/config/system/local-footer');
    $I->checkOption('#edit-su-footer-enabled-value');
    $I->click('#edit-group-primary-links summary');
    $I->click('#edit-group-secondary-links summary');

    $I->fillField('su_local_foot_primary[0][uri]', 'https://google.com');
    $I->fillField('su_local_foot_primary[0][title]', 'Primary Link');
    $I->click('Add another item', '.field--name-su-local-foot-primary');
    $I->waitForElement('[name="su_local_foot_primary[1][uri]"]');
    $I->fillField('su_local_foot_primary[1][uri]', 'https://stanford.edu');
    $I->fillField('su_local_foot_primary[1][title]', 'Another primary link');

    $I->fillField('su_local_foot_second[0][uri]', 'https://stanford.edu');
    $I->fillField('su_local_foot_second[0][title]', 'Secondary Link');
    $I->click('Add another item', '.field--name-su-local-foot-second');
    $I->waitForElement('[name="su_local_foot_second[1][uri]"]');
    $I->fillField('su_local_foot_second[1][uri]', 'https://google.com');
    $I->fillField('su_local_foot_second[1][title]', 'Another secondary link');

    $I->click('Save');

    // Validate email links.
    $I->amOnPage('/');

    // $mails = $I->grabMultiple('a.mailto svg.mailto');
    // $I->assertEquals(count($mails), 3);

    // External Links in the page-content region.
    // $pageExternals = $I->grabMultiple('#page-content a.su-link--external svg.su-link--external');
    // $I->assertEquals(count($pageExternals), 1);

    // External links in the local footer.
    $I->canSeeNumberOfElements('.su-local-footer__cell2 a.su-link--external svg.su-link--external', 4);
  }

}
