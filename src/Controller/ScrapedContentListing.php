<?php

namespace Drupal\web_scraper\Controller;

use Drupal\Component\Utility\Html;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Displays a table of Scraped Content
 */
class ScrapedContentListing extends ControllerBase {

  /**
   * A database connection object.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The currently selected language.
   *
   * @var \Drupal\Core\Language\Language
   */
  protected $currentLanguage;

  /**
   * A config object for the system performance configuration.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Constructs the controller
   *
   * @param \Drupal\Core\Database\Connection $database
   *   A database connection object.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(Connection $database, LanguageManagerInterface $language_manager, ConfigFactoryInterface $config_factory) {
    $this->database = $database;
    $this->currentLanguage = $language_manager->getCurrentLanguage();
    $this->config = $config_factory->get('web_scraper.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('language_manager'),
      $container->get('config.factory')
    );
  }

  /**
   * Builds the sortable table
   */
  public function build() {

    $langcode = $this->currentLanguage->getId();

    $query = $this->database->select('scraped_content_field_data', 'sc')
      ->extend('Drupal\Core\Database\Query\TableSortExtender')
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender');
    $query->condition('langcode', $langcode);
    $query->fields('sc', [
      'id',
      'headline',
      'article_status',
      'editor'
    ]);

    $header = [
      ['data' => $this->t('Headline'), 'field' => 'headline', 'sort' => 'asc'],
      ['data' => $this->t('Status'), 'field' => 'article_status'],
      ['data' => $this->t('Editor'), 'field' => 'editor']
    ];

    $results = $query
      ->limit($this->config->get('number_of_items_to_list'))
      ->orderByHeader($header)
      ->execute();

    $rows = [];
    foreach ($results as $result) {
      $url = Url::fromRoute('entity.scraped_content.canonical', array('scraped_content' => $result->id));
      $rows[] = [
        'data' => [
          Link::fromTextAndUrl($result->headline, $url)->toString(),
          $result->article_status,
          Html::escape($result->editor)
        ]
      ];
    }

    $build['entity_list_table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('There are no Scraped Content rows, yet.'),
    ];

    $build['pager'] = ['#type' => 'pager'];

    return $build;
  }

}
