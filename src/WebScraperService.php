<?php

namespace Drupal\web_scraper;

use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\web_scraper\Entity\ScrapedContent;
use Drupal\web_scraper\ScrapedContentDataValidationInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Defines a web scraper service.
 */
class WebScraperService implements WebScraperInterface {

  use StringTranslationTrait;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * The logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * The data validation service.
   *
   * @var \Drupal\web_scraper\ScrapedContentDataValidationInterface
   */
  protected $dataValidationService;

  /**
   * Constructs a Drupal\web_scraper\WebScraperInterface object.
   *
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger channel factory.
   * @param \Drupal\web_scraper\ScrapedContentDataValidationInterface $validation_service
   *   The data validation service
   */
  public function __construct(LanguageManagerInterface $language_manager, LoggerChannelFactoryInterface $logger_factory, ScrapedContentDataValidationInterface $validation_service) {
    $this->languageManager = $language_manager;
    $this->loggerFactory = $logger_factory;
    $this->dataValidationService = $validation_service;
  }

  /**
   * {@inheritdoc}
   */
  public function addArticle($data, $source) {
    if ($this->dataValidationService->scrapedDataHasRequiredFields($data)) {
      $parsed_data['scraped_h1'] = $this->parseH1($data);
      $parsed_data['scraped_title'] = $this->parseTitle($data);
      $parsed_data['scraped_content'] = $this->parseContent($data);
      $scrapedArticleItem = ScrapedContent::create(
        [
          'headline' => $parsed_data['scraped_title'],
          'article_body' => $parsed_data['scraped_content'],
          'editor' => 0,
          'article_status' => 'scraped',
          'langcode' => $this->languageManager->getDefaultLanguage()->getId(),
          'scraped_h1' => $parsed_data['scraped_h1'],
          'scraped_title' => $parsed_data['scraped_title'],
          'scraped_content' => $parsed_data['scraped_content'],
          'source' => $source
        ]
      );
      try {
        $scrapedArticleItem->save();
        $this->loggerFactory->get('web_scraper')
          ->notice('Created Scraped Content item with ID %id.', ['%id' => $scrapedArticleItem->id()]);
        $this->drupalSetMessage($this->t('Created Scraped Content item with ID %id.', ['%id' => $scrapedArticleItem->id()]), 'status'));
      }
      catch (EntityStorageException $e) {
        throw new HttpException(500, 'Internal Server Error', $e);
      }
      return $scrapedArticleItem;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function addArticleTranslation($data) {
    if ($this->dataValidationService->translationDataHasRequiredFields($data) &&
      $this->dataValidationService->sourceArticleExists($data) &&
      $this->dataValidationService->translationDataIsValid($data)) {
      $translatedArticleItem = ScrapedContent::addTranslation(
        $data['language_code'],
        [
          'headline' => $data['headline'],
          'article_body' => $data['article_body'],
          'editor' => $data['editor'],
          'article_status' => 'translated'
        ]
      );
      try {
        $translatedArticleItem->save();
        $this->loggerFactory->get('web_scraper')
          ->notice('Created Scraped Content translation for ID %id with language %language_code.', [
            '%id' => $scrapedArticleItem->id(),
            '%language_code' => $data['language_code']
          ]);
        $this->drupalSetMessage($this->t('Created Scraped Content translation for ID %id with language %language_code.', [
          '%id' => $translatedArticleItem->id(),
          '%language_code' => $data['language_code']
        ]), 'status'));
      }
      catch (EntityStorageException $e) {
        throw new HttpException(500, 'Internal Server Error', $e);
      }
      return $translatedArticleItem;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function parseContent($data) {
    $content = 'content coming soon';
    return $content;
  }

  /**
   * {@inheritdoc}
   */
  public function parseH1($data) {
    $h1 = 'h1 coming soon';
    return $h1;
  }

  /**
   * {@inheritdoc}
   */
  public function parseTitle($data) {
    $title = 'title coming soon';
    return $title;
  }

/*****************************************************************************
 **                                                                         **
 ** We use this method so that it's easy to unit test our class. For testing**
 ** we will create a helper class that extends this one. In that helper     **
 ** class we will override this method so that it's a no-op method.         **
 **                                                                         **
 *****************************************************************************/
  /**
   * Sends a message using drupal_set_message().
   *
   * @param string $message
   *   The message to send
   * @param string $type
   *   The message type
   */
  protected function drupalSetMessage($message, $type) {
    drupal_set_message($message, $type);
  }

}
