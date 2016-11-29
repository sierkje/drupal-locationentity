<?php

namespace Drupal\Tests\locationentity\Unit;

use \Drupal\locationentity\Messenger\ProceduralWrapperMessenger;

/**
 * @group locationentity
 * @coversDefaultClass Drupal\locationentity\Messenger\ProceduralWrapperMessenger
 */
class ProceduralWrapperMessengerTest extends \PHPUnit_Framework_TestCase {

  /**
   * The 'drupal_set_message wrapper' messenger.
   *
   * @var \Drupal\locationentity\Messenger\ProceduralWrapperMessenger
   */
  protected $messenger;

  /**
   * Function setUp.
   *
   * @todo Replace with proper function description.
   */
  protected function setUp() {
    $this->messenger = new ProceduralWrapperMessenger();
    parent::setUp();
  }

  /**
   * @covers ::deleteStatusMessages
   */
  public function testDeleteStatusMessages() {
    $this->messenger->addStatusMessage('Test this string.');
    $this->messenger->deleteStatusMessages();

    $this->assertEmpty($this->messenger->getStatusMessages());
  }
}

