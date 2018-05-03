<?php

namespace Drupal\web_scraper;

/**
 * Defines an interface that provides functionality for the web scraper service.
 */
interface WebScraperInterface {

  /**
   * Gets the edited article body.
   *
   * @param string $data
   *   The scraped data.
   */
  public function addArticle($data);

}
