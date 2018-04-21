<?php

namespace Drupal\Tests\web_scraper\Functional;

use Drupal\web_scraper\Entity\ScrapedContent;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests the Scraped Content listing controller.
 *
 * @group web_scraper
 */
class ScrapedContentListingFunctionalTest extends BrowserTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = array('web_scraper');

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setup();

    $now = time();

/******************************************************************************
 **                                                                          **
 ** I am not testing multilingual; it would be a good thing to test it,      **
 ** though. I'd need to install another language and create some entities in **
 ** that language.                                                           **
 **                                                                          **
 ******************************************************************************/
    $scrapedContentEntity = ScrapedContent::create(array(
      'label' => t('First Entity'),
      'langcode' => 'en',
      'ot_coordinates' => '47.7231° N, 86.9407° W',
      'ot_depth' => 1.5,
      'ot_temperature' => 58.0,
      'ot_reported_date' => $now,
      'ot_reporter' => 'ScrapedContentEntityTest.php'
    ));
    $scrapedContentEntity->save();
    $scrapedContentEntity = ScrapedContent::create(array(
      'label' => t('Second Entity'),
      'langcode' => 'en',
      'ot_coordinates' => '47.7231° N, 86.9407° W',
      'ot_depth' => 1.5,
      'ot_temperature' => 57.0,
      'ot_reported_date' => $now - 1 * 365 * 24 * 60 * 60,
      'ot_reporter' => 'ScrapedContentEntityTest.php'
    ));
    $scrapedContentEntity->save();
    $scrapedContentEntity = ScrapedContent::create(array(
      'label' => t('Third Entity'),
      'langcode' => 'en',
      'ot_coordinates' => '47.7231° N, 86.9407° W',
      'ot_depth' => 1.5,
      'ot_temperature' => 56.0,
      'ot_reported_date' => $now - 2 * 365 * 24 * 60 * 60,
      'ot_reporter' => 'ScrapedContentEntityTest.php'
    ));
    $scrapedContentEntity->save();
    $scrapedContentEntity = ScrapedContent::create(array(
      'label' => t('Fourth Entity'),
      'langcode' => 'en',
      'ot_coordinates' => '47.7231° N, 86.9407° W',
      'ot_depth' => 1.5,
      'ot_temperature' => 55.0,
      'ot_reported_date' => $now - 3 * 365 * 24 * 60 * 60,
      'ot_reporter' => 'ScrapedContentEntityTest.php'
    ));
    $scrapedContentEntity->save();
    $scrapedContentEntity = ScrapedContent::create(array(
      'label' => t('Fifth Entity'),
      'langcode' => 'en',
      'ot_coordinates' => '47.7231° N, 86.9407° W',
      'ot_depth' => 1.5,
      'ot_temperature' => 54.0,
      'ot_reported_date' => $now - 4 * 365 * 24 * 60 * 60,
      'ot_reporter' => 'ScrapedContentEntityTest.php'
    ));
    $oceanTemperatureEntity->save();
  }

  /**
   * Tests the controller listings.
   */
  public function testControllerListing() {

    $testUser = $this->drupalCreateUser(array(
      'view scraped_content entity',
    ));

    $this->drupalLogin($testUser);
    $this->drupalGet('/scraped_content/entity-list');
    $page_text = $this->getTextContent();
    $this->assertContains('Fifth Entity', $page_text);
    $this->assertContains('First Entity', $page_text);
    $this->assertContains('Fourth Entity', $page_text);
    $this->assertContains('Page 2', $page_text);
    $this->clickLink('Label');
    $page_text = $this->getTextContent();
    $this->assertContains('Third Entity', $page_text);
    $this->assertContains('Second Entity', $page_text);
    $this->assertContains('Fourth Entity', $page_text);
    $this->clickLink('Third Entity');
    $page_text = $this->getTextContent();
    $this->assertContains('56.0', $page_text);

/******************************************************************************
 **                                                                          **
 ** If I were testing multilingual I would either need add more tests here   **
 ** or I would add another test to check how the listing works when I        **
 ** navigate to a listing using the translated language.                     **
 **                                                                          **
 ******************************************************************************/
  }

}
