<?php

namespace Drupal\web_scraper;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/******************************************************************************
 **                                                                          **
 ** Even if we had not required any new methods beyond what                  **
 ** ContentEntityInterface and EntityChangedInterface require, best          **
 ** practice dictates that we create our own interface. Having an interface  **
 ** specifically for our entity allows the possibility to properly type hint **
 ** without having to use the class name itself. Recall that we want to      **
 ** support dependency injection and the ability to write custom             **
 ** implementations without having to hack code.                             **
 **                                                                          **
 ******************************************************************************/

/**
 * Provides an interface for the Scraped Content entity.
 */
interface ScrapedContentInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Gets the edited article body.
   *
   * @return string
   *   The body text.
   */
  public function getArticleBody();

  /**
   * Gets the created time.
   *
   * @return int
   *   The creation timestamp of the scraped content.
   */
  public function getCreatedTime();

  /**
   * Gets the article editor.
   *
   * @return string
   *   The name of the editor.
   */
  public function getEditor();

  /**
   * Gets the article headline
   *
   * @return string
   *   The headline.
   */
  public function getHeadline();

  /**
   * Gets the scraped content.
   *
   * @return string
   *   The scraped content.
   */
  public function getScrapedContent();

  /**
   * Gets the scraped h1.
   *
   * @return string
   *   The scraped h1.
   */
  public function getScrapedH1();

  /**
   * Gets the scraped title.
   *
   * @return string
   *   The scraped title.
   */
  public function getScrapedTitle();

  /**
   * Gets the source URL.
   *
   * @return string
   *   The source.
   */
  public function getSource();

  /**
   * Gets the article status.
   *
   * @return string
   *   The status.
   */
  public function getStatus();

  /**
   * Sets the article body.
   *
   * @param string $body_text
   *   The edited body text for the article.
   *
   * @return \Drupal\web_scraper\ScrapedContentInterface
   *   The updated entity.
   */
  public function setArticleBody($body_text);

  /**
   * Sets the editor's name.
   *
   * @param string $name
   *   The name of the editor.
   *
   * @return \Drupal\web_scraper\ScrapedContentInterface
   *   The updated entity.
   */
  public function setEditor($name);

  /**
   * Sets the headline.
   *
   * @param string $headline
   *   The headline of the article.
   *
   * @return \Drupal\web_scraper\ScrapedContentInterface
   *   The updated entity.
   */
  public function setHeadline($headline);

  /**
   * Sets the article status.
   *
   * @param string $status
   *   The status of the article.
   *   [scraped | published | rejected ]
   *
   * @return \Drupal\web_scraper\ScrapedContentInterface
   *   The updated entity.
   */
  public function setStatus($status);

}
