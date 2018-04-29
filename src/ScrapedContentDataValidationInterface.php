<?php

namespace Drupal\web_scraper;

/**
 * Defines an interface that provides functionality to validation services.
 */
interface ScrapedContentDataValidationInterface {

  /**
   * Validate required fields
   *
   * @param array $data
   *   The information that was passed
   *
   * @return boolean
   */
  public function hasRequiredFields($data);

  /**
   * Validate coordinates
   *
   * @param string $status
   *   The status of the article
   *
   * @return boolean
   */
  public function isValidStatus($status);

  /**
   * Validate editor
   *
   * @param int $editor
   *   The uid of the editor
   *
   * @return boolean
   */
  public function isValidEditor($editor);

}
