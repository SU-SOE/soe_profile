<?php

namespace Drupal\Tests\soe_profile\Unit\Plugin\HelpSection;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Utility\LinkGeneratorInterface;
use Drupal\soe_profile\Plugin\HelpSection\ProfileConnectSection;
use Drupal\Tests\UnitTestCase;

/**
 * Class ProfileConnectSectionTest
 *
 * @group soe_profile
 * @coversDefaultClass \Drupal\soe_profile\Plugin\HelpSection\ProfileConnectSection
 */
class ProfileConnectSectionTest extends UnitTestCase {

  /**
   * {@inheritDoc}
   */
  protected function setUp() {
    parent::setUp();
    $container = new ContainerBuilder();
    $container->set('string_translation', $this->getStringTranslationStub());

    $container->set('link_generator', $this->createMock(LinkGeneratorInterface::class));;
    \Drupal::setContainer($container);
  }

  /**
   * Test the connection topics exist.
   */
  public function testHelpSections() {
    $plugin = new ProfileConnectSection([], '', []);
    $topics = $plugin->listTopics();
    $this->assertCount(1, $topics);
  }

}
