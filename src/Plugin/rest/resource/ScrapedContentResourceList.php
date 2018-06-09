<?php

namespace Drupal\web_scraper\Plugin\rest\resource;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Provides a resource to list scraped content items.
 *
 * @RestResource(
 *   id = "scraped_content_list",
 *   label = @Translation("Scraped Content item list"),
 *   uri_paths = {
 *     "canonical" = "/web_scraper/list/{from}/{to}"
 *   }
 * )
 */
class ScrapedContentResourceList extends ResourceBase {

  /**
   * The currently selected language.
   *
   * @var \Drupal\Core\Language\Language
   */
  protected $currentLanguage;

  /**
   * The entity storage for scraped content items.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $scrapedContentStorage;

  /**
   * Constructs a Drupal\web_scraper\Plugin\rest\resource\ScrapedContentResourceList object.
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
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, $serializer_formats, LoggerInterface $logger, LanguageManagerInterface $language_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->currentLanguage = $language_manager->getCurrentLanguage();
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
      $container->get('language_manager')
    );
  }

  /**
   * Responds to GET requests.
   *
   * Returns a list of scraped content items.
   *
   * @param string $from
   *   The from date (YYYYMMDD).
   * @param string $to
   *   The to date (YYYYMMDD).
   *
   * @return \Drupal\rest\ResourceResponse
   *   The response containing the list of items.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   */
  public function get($from, $to) {

    $query = $this->scrapedContentStorage->getQuery()
      ->condition('langcode', $this->currentLanguage->getId())
      ->condition('post_date', [$from, $to], 'BETWEEN');
      ->sort('headline', 'ASC');
    $result = $query->execute();

    if ($result) {
      $items = $this->scrapedContentStorage->loadMultiple($result);
      foreach ($items as $item) {
        $translatedItem = $item->getTranslation($this->currentLanguage->getId());

        $itemAccess = $translatedItem->access('view', NULL, TRUE);
        if ($itemAccess->isAllowed()) {
          $record[] = [
            'id' => $item->id->value,
            'headline' => $translatedItem->getHeadline()
          ];
        }
      }
    }

    if (!empty($record)) {
      $response = new ResourceResponse($record);
      $response->addCacheableDependency(CacheableMetadata::createFromRenderArray([
        '#cache' => [
          'tags' => [
            'scraped_content_list',
          ],
        ],
      ]));
      return $response;
    }

    throw new NotFoundHttpException(t('No scraped content data items were found.'));

  }
}
