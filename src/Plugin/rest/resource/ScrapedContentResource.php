<?php

namespace Drupal\web_scraper\Plugin\rest\resource;

use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\web_scraper\ScrapedContentDataValidationInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Provides a resource for a scraped content data item.
 *
 * @RestResource(
 *   id = "scraped_content_item",
 *   label = @Translation("Scraped Content item"),
 *   uri_paths = {
 *     "canonical" = "/web_scraper/item/{id}",
 *     "https://www.drupal.org/link-relations/create" = "/web_scraper/item"
 *   }
 * )
 */
class ScrapedContentResource extends ResourceBase {

  /**
   * The currently selected language.
   *
   * @var \Drupal\Core\Language\Language
   */
  protected $currentLanguage;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The data validation service.
   *
   * @var \Drupal\web_scraper\ScrapedContentDataValidationInterface
   */
  protected $dataValidationService;

  /**
   * The entity storage for scraped content items.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $scrapedContentStorage;

  /**
   * Constructs a Drupal\web_scraper\Plugin\rest\resource\ScrapedContentResource object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The currently logged in user.
   * @param \Drupal\web_scraper\ScrapedContentDataValidationInterface $data_validation_service
   *   The data validation service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, $serializer_formats, LoggerInterface $logger, LanguageManagerInterface $language_manager, AccountInterface $current_user, ScrapedContentDataValidationInterface $data_validation_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->currentLanguage = $language_manager->getCurrentLanguage();
    $this->languageManager = $language_manager;
    $this->currentUser = $current_user;
    $this->dataValidationService = $data_validation_service;
    $this->scrapedContentStorage = $entity_type_manager->getStorage('scraped_content');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('rest'),
      $container->get('language_manager'),
      $container->get('current_user'),
      $container->get('scraped_content.data_validation_service')
    );
  }

  /**
   * Responds to entity GET requests.
   *
   * Returns a scraped content data item for the specified ID.
   *
   * @param string $id
   *   The ID of the object.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The response containing the entity with its accessible fields.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   */
  public function get($id) {
    if ($scrapedContent = $this->scrapedContentStorage->load($id)) {
      $translatedScrapedContent = $scrapedContent->getTranslation($this->currentLanguage->getId());

      $scrapedContentAccess = $translatedScrapedContent->access('view', NULL, TRUE);
      if (!$scrapedContentAccess->isAllowed()) {
        throw new AccessDeniedHttpException();
      }
      $record = [
        'headline' => $translatedScrapedContent->getHeadline(),
        'status' => $translatedScrapedContent->getArticleStatus(),
        'editor' => $translatedScrapedContent->getEditor()->getDisplayName()
      ];
    }

    if (!empty($record)) {
      $response = new ResourceResponse($record, 200);
      $response->addCacheableDependency($translatedScrapedContent);
      return $response;
    }

    throw new NotFoundHttpException(t('Scraped Content item with ID @id was not found', array('@id' => $id)));
  }

  /**
   * Responds to POST requests and saves a new scraped content item.
   *
   * @param array $data
   *   The POST data.
   *
   * @return \Drupal\rest\ModifiedResourceResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   */
  public function post(array $data) {
    // @todo: write a client to test this method
    if ($data == NULL) {
      throw new BadRequestHttpException('No data received.');
    }

    if (!$this->currentUser->hasPermission('add scraped_content entity')) {
      throw new AccessDeniedHttpException();
    }

    if (!isset($data['article_id'])) {
      throw new BadRequestHttpException('Article ID not passed.');
    }

    $scrapedContentItem = $this->scrapedContentStorage->load($data['article_id']);
    if (!$scrapedContentItem) {
      throw new BadRequestHttpException('Article not found with passed ID.');
    }

    if (isset($data['language_code']) && (!in_array($data['language_code'], array_keys($this->languageManager->getLanguages())))) {
      throw new BadRequestHttpException('Language not defined on site.');
    }
    if (!isset($data['language_code'])) {
      $data['language_code'] = 'en';
    }

    if ($scrapedContentItem->hasTranslation($data['language_code'])) {
      throw new BadRequestHttpException('Language translation already exists.');
    }

    if (!$this->dataValidationService->hasRequiredFields($data)) {
      $dataIsValid = FALSE;
    }
    elseif (!$this->dataValidationService->isValidEditor($data['editor'])) {
      $dataIsValid = FALSE;
    }
    else {
      $dataIsValid = TRUE;
    }
    if ($dataIsValid) {
      $scrapedContentItem->addTranslation(
        $data['language_code'],
        [
          'headline' => $data['headline'],
          'article_status' => 'translated',
          'editor' => $data['editor'],
          'article_body' => $data['body']
        ]
      );
      try {
        $scrapedContentItem->save();

        $this->logger->notice('Created Scraped Content @language translation for item with ID %id.', array('%id' => $scrapedContentItem->id(), '@language' => $data['language_code']));

        $url = $scrapedContentItem->toUrl('canonical', ['absolute' => TRUE, 'language' => $this->languageManager->getLanguage($data['language_code'])])->toString(TRUE);

        $response = new ModifiedResourceResponse($scrapedContentItem, 201, ['Location' => $url->getGeneratedUrl()]);
        return $response;
      }
      catch (EntityStorageException $e) {
        throw new HttpException(500, 'Internal Server Error', $e);
      }
    }
    else {
      throw new BadRequestHttpException('Invalid data received.');
    }
  }

}
