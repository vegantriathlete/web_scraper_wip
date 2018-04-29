<?php

namespace Drupal\web_scraper\Controller;

use Drupal\Component\Utility\Html;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Datetime\DateFormatterInterface;
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
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * Constructs the controller
   *
   * @param \Drupal\Core\Database\Connection $database
   *   A database connection object.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The language manager.
   */
  public function __construct(Connection $database, LanguageManagerInterface $language_manager, DateFormatterInterface $date_formatter) {
    $this->database = $database;
    $this->currentLanguage = $language_manager->getCurrentLanguage();
    $this->dateFormatter = $date_formatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('language_manager'),
      $container->get('date.formatter')
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

    // @todo: Make the limit configurable
    $results = $query
      ->limit(3)
      ->orderByHeader($header)
      ->execute();

    $rows = [];
    foreach ($results as $result) {
      $url = Url::fromRoute('entity.scraped_content.canonical', array('scraped_content' => $result->id));
      $rows[] = [
        'data' => [
          Link::fromTextAndUrl($result->headline, $url)->toString(),
          $result->article_status,
          Html::escape($result->editor->getDisplayName())
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
