<?php

namespace Drupal\Tests\soe_profile\Unit\Config;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\State\StateInterface;
use Drupal\soe_profile\Config\ConfigOverrides;
use Drupal\Tests\UnitTestCase;

/**
 * Class ConfigOverridesTest
 *
 * @group soe_profile
 * @coversDefaultClass \Drupal\soe_profile\Config\ConfigOverrides
 */
class ConfigOverridesTest extends UnitTestCase {

  /**
   * @var \Drupal\soe_profile\Config\ConfigOverrides
   */
  protected $overrideService;

  /**
   * {@inheritDoc}
   */
  protected function setUp() {
    parent::setUp();
    $state = $this->createMock(StateInterface::class);
    $state->method('get')->will($this->returnCallback([$this, 'getStateCallback']));
    $this->overrideService = new ConfigOverrides($state);
  }

  public function testConfigOverrides() {
    $this->assertEquals('SoeProfileConfigOverride', $this->overrideService->getCacheSuffix());
    $this->assertNull($this->overrideService->createConfigObject('foo'));
    $this->assertInstanceOf(CacheableMetadata::class, $this->overrideService->getCacheableMetadata('foo'));

    $overrides = $this->overrideService->loadOverrides(['system.site']);
    $this->assertArrayEquals([
      'page' => [
        403 => '/node/403',
        404 => '/node/404',
        'front' => '/node/99',
      ],
    ], $overrides['system.site']);
  }

  public function getStateCallback($name) {
    switch ($name) {
      case 'soe_profile.403_page':
        return '/node/403';

      case 'soe_profile.404_page':
        return '/node/404';

      case 'soe_profile.front_page':
        return '/node/99';

    }
  }

}
