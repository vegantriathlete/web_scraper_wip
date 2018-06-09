<?php

namespace Drupal\Tests\web_scrapter\Kernel;

use Drupal\web_scraper\Entity\ScrapedContent;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;

/**
 * Tests the Scraped Content Entity.
 *
 * @group web_scraper
 */
class IaiOceanTemperatureDataEntityTest extends EntityKernelTestBase {

  // @todo: Review the whole Kernel test implementation
  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = [
    'web_scraper'
  ];

  /**
   * The creation time of the entity
   */
  protected $reportedDate;

  /**
   * The created entity
   */
  protected $entity;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setup();

    $this->installEntitySchema('scraped_content');

    $this->reportedDate = time();

    // Create an ocean temperature data entity
    $scrapedContentEntity = ScrapedContent::create(array(
      'label' => t('Kernel Test of Scraped Content'),
      'langcode' => 'en',
      'ot_coordinates' => '47.7231° N, 86.9407° W',
      'ot_depth' => 1.5,
      'ot_temperature' => 58.0,
      'ot_reported_date' => $this->reportedDate,
      'ot_reporter' => 'ScrapedContentEntityTest.php'
    ));
    $scrapedContentEntity->save();
    $this->entity = $scrapedContentEntity;

  }

  /**
   * Tests the entity's getLabel method
   */
  public function testGetLabelReturnsLabel() {
    $this->assertEquals('Kernel Test of Scraped Content', $this->entity->getLabel());
  }

  /**
   * Tests the entity's getCoordinates method
   */
  public function testGetCoordinates() {
    $this->assertEquals('47.7231° N, 86.9407° W', $this->entity->getCoordinates());
  }

  /**
   * Tests the entity's getDepth method
   */
  public function testGetDepth() {
    $this->assertEquals(1.5, $this->entity->getDepth());
  }

  /**
   * Tests the entity's getTemperature method
   */
  public function testGetTemperature() {
    $this->assertEquals(58.0, $this->entity->getTemperature());
  }

  /**
   * Tests the entity's getReportedDate method
   */
  public function testGetReportedDate() {
    $this->assertEquals($this->reportedDate, $this->entity->getReportedDate());
  }

  /**
   * Tests the entity's getReporter method
   */
  public function testGetReporter() {
    $this->assertEquals('ScrapedContentEntityTest.php', $this->entity->getReporter());
  }

}
