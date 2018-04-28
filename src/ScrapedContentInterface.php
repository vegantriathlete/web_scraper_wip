<?php

namespace Drupal\web_scraper;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\UserInterface;

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
   * Gets the article status.
   *
   * @return string
   *   The status.
   */
  public function getArticleStatus();

  /**
   * Gets the created time.
   *
   * @return int
   *   The creation timestamp of the scraped content.
   */
  public function getCreatedTime();

  /**
   * Gets the article's editor entity.
   *
   * @return \Drupal\user\UserInterface
   *   The editor user entity.
   */
  public function getEditor();

  /**
   * Returns the entity editor's user ID.
   *
   * @return int|null
   *   The editor user ID, or NULL in case the user ID field has not been set on
   *   the entity.
   */
  public function getEditorId();

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
   * Sets the article status.
   *
   * @param string $status
   *   The status of the article.
   *   [scraped | published | rejected ]
   *
   * @return \Drupal\web_scraper\ScrapedContentInterface
   *   The updated entity.
   */
  public function setArticleStatus($status);

  /**
   * Sets the entity editor's user entity.
   *
   * @param \Drupal\user\UserInterface $account
   *   The editor user entity.
   *
   * @return \Drupal\web_scraper\ScrapedContentInterface
   *   The updated entity.
   */
  public function setEditor(UserInterface $account);

  /**
   * Sets the entity editor's user ID.
   *
   * @param int $uid
   *   The editor user id.
   *
   * @return \Drupal\web_scraper\ScrapedContentInterface
   *   The updated entity.
   */
  public function setEditorId($uid);

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

}
