<?php

namespace Drupal\Tests\soe_profile\Unit\EventSubscriber;

use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\default_content\Event\ImportEvent;
use Drupal\file\FileInterface;
use Drupal\node\NodeInterface;
use Drupal\soe_profile\EventSubscriber\EventSubscriber as StanfordEventSubscriber;
use Drupal\Tests\UnitTestCase;

if (!defined('DRUPAL_ROOT')) {
  define('DRUPAL_ROOT', __DIR__);
}

/**
 * Class EventSubscriberTest.
 *
 * @group soe_profile
 * @coversDefaultClass \Drupal\soe_profile\EventSubscriber\EventSubscriber
 */
class EventSubscriberTest extends UnitTestCase {

  /**
   * Event subscriber object.
   *
   * @var \Drupal\soe_profile\EventSubscriber\EventSubscriber
   */
  protected $eventSubscriber;

  /**
   * {@inheritDoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $file_system = $this->createMock(FileSystemInterface::class);
    $logger_factory = $this->createMock(LoggerChannelFactoryInterface::class);

    $this->eventSubscriber = new TestStanfordEventSubscriber($file_system, $logger_factory);
  }

  /**
   * Test what we can of the content import event listener.
   */
  public function testConfigImportEventDownload() {
    $expected = ['default_content.import' => 'onContentImport'];
    $this->assertArrayEquals($expected, StanfordEventSubscriber::getSubscribedEvents());

    $file_entity = $this->createMock(FileInterface::class);
    $file_entity->method('getEntityTypeId')->willReturn('file');
    $file_entity->method('getFileUri')->willReturn('/foo-bar.jpg');
    $node_entity = $this->createMock(NodeInterface::class);

    $entities = [$file_entity, $node_entity];
    $event = new ImportEvent($entities, 'foo_bar');
    $this->assertNull($this->eventSubscriber->onContentImport($event));
  }

}

/**
 * {@inheritDoc}
 */
class TestStanfordEventSubscriber extends StanfordEventSubscriber {

  /**
   * {@inheritDoc}
   */
  protected function downloadFile($source, $destination) {
    return $destination;
  }

}
