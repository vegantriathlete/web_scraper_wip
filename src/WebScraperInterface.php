<?php

namespace Drupal\web_scraper;

/**
 * Defines an interface that provides functionality for the web scraper service.
 */
interface WebScraperInterface {

  /**
   * Creates a new article from scraped content
   *
   * @param string $data
   *   The scraped data.
   * @param string $source
   *   The source URL.
   *
   * @returns \Drupal\web_scraper\ScrapedContentInterface
   */
  public function addArticle($data, $source);

  /**
   * Parses the content from the scraped data
   *
   * @param string $data
   *   The scraped data.
   *
   * @returns string $content
   *   The content (article body).
   */
  public function parseContent($data);

  /**
   * Parses the h1 tag from the scraped data
   *
   * @param string $data
   *   The scraped data.
   *
   * @returns string $h1
   *   The h1 data.
   */
  public function parseH1($data);

  /**
   * Parses the title tag from the scraped data
   *
   * @param string $data
   *   The scraped data.
   *
   * @returns string $title
   *   The title data.
   */
  public function parseTitle($data);

}
