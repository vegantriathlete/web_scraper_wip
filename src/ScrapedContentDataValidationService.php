<?php

namespace Drupal\web_scraper;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Defines the data validation service.
 */
class ScrapedContentDataValidationService implements ScrapedContentDataValidationInterface {

  /**
   * The user storage service
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $userStorage;

  /**
   * Constructs a Drupal\web_scraper\ScrapedContentDataValidationInterface object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->userStorage = $entity_type_manager->getStorage('user');
  }

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
    if ($account = $this->userStorage->load($editor)) {
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
