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
    if ($data['headline'] != '' && $data['editor'] && $data['article_body'] != '') {
      return TRUE;
    }
    return FALSE;
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

  /**
   * {@inheritdoc}
   */
  public function scrapedDataHasRequiredFields($data) {
    return (strpos($data, '<h1') !== FALSE &&
      strpos($data, '<title') !== FALSE &&
      strpos($data, '<body') !== FALSE &&
      strpos($data, '<p') !== FALSE
    );
  }

}
