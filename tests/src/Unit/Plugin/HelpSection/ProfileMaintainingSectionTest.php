<?php

namespace Drupal\Tests\soe_profile\Unit\Plugin\HelpSection;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Utility\LinkGeneratorInterface;
use Drupal\soe_profile\Plugin\HelpSection\ProfileHelpMaintainingSection;
use Drupal\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\Group;

/**
 * Class ProfileMaintainingSectionTest
 *
 * @group soe_profile
 * @coversDefaultClass \Drupal\soe_profile\Plugin\HelpSection\ProfileHelpMaintainingSection
 */
#[Group('stanford_profile')]
class ProfileMaintainingSectionTest extends UnitTestCase {

  /**
   * {@inheritDoc}
   */
  protected function setUp(): void {
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
    $plugin = new ProfileHelpMaintainingSection([], '', []);
    $topics = $plugin->listTopics();
    $this->assertCount(3, $topics);
  }

}
