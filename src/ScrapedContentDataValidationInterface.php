<?php

namespace Drupal\web_scraper;

/**
 * Defines an interface that provides functionality to validation services.
 */
interface ScrapedContentDataValidationInterface {

  // @todo: Consider adding isValidDate

  /**
   * Validates the POST data
   *
   * @param string $data
   *   The data being passed in the POST
   *
   * @return boolean
   */
  public function hasRequiredFields($data);

  /**
   * Validates the status
   *
   * @param string $status
   *   The status of the article
   *
   * @return boolean
   */
  public function isValidStatus($status);

  /**
   * Validates the editor
   *
   * @param int $editor
   *   The uid of the editor
   *
   * @return boolean
   */
  public function isValidEditor($editor);

  /**
   * Validates the scraped data has required fields
   *
   * @param array $data
   *   The information that was scraped
   *
   * @return boolean
   */
  public function scrapedDataHasRequiredFields($data);

}
