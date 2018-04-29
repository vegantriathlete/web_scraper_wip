<?php

namespace Drupal\web_scraper;

/**
 * Defines the data validation service.
 */
class ScrapedContentDataValidationService implements ScrapedContentDataValidationInterface {

  /**
   * {@inheritdoc}
   */
  public function hasRequiredFields($data) {
    if (isset($data['headline']) &&
        isset($data['body']) &&
        isset($data['status']) &&
        isset($data['editor'])) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function isValidStatus($status) {
    if ($status == 'scraped' || $status == 'published' || $status == 'rejected') {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function isValidEditor($editor) {
    // @todo: Validate the uid exists
    if (is_numeric($editor)) {
      return TRUE;
    }
    return FALSE;
  }

}
