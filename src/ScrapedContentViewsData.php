<?php

namespace Drupal\web_scraper;

use Drupal\views\EntityViewsData;

/******************************************************************************
 **                                                                          **
 ** To understand how to write this class                                    **
 ** @see: https://api.drupal.org/api/drupal/core%21modules%21views%21views.api.php/function/hook_views_data/8.5.x  **
 **                                                                          **
 ******************************************************************************/

/**
 * Provides the Views data for the Scraped Content entity.
 */
class ScrapedContentViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['scraped_content_field_data']['table']['group'] = $this->t('Scraped Content Table');
    $data['scraped_content_field_data']['table']['base'] = [
      'field' => 'id',
      'title' => $this->t('Scraped Content'),
      'help' => $this->t('This data is reported via a RESTful interface and is also programatically and manually translated'),
    ];

/******************************************************************************
 **                                                                          **
 ** To find the field handlers that Views core understands                   **
 ** @see: https://api.drupal.org/api/drupal/core%21modules%21views%21src%21Plugin%21views%21field%21FieldPluginBase.php/group/views_field_handlers/8.5.x **
 **                                                                          **
 ** To find the argument handlers that Views core understands                **
 ** @see: https://api.drupal.org/api/drupal/core%21modules%21views%21src%21Plugin%21views%21argument%21ArgumentPluginBase.php/group/views_argument_handlers/8.5.x **
 **                                                                          **
 ** To find the filter handlers that Views core understands                  **
 ** @see: https://api.drupal.org/api/drupal/core%21modules%21views%21src%21Plugin%21views%21filter%21FilterPluginBase.php/group/views_filter_handlers/8.5.x **
 **                                                                          **
 ** To find the sort handlers that Views core understands                    **
 ** @see: https://api.drupal.org/api/drupal/core%21modules%21views%21src%21Plugin%21views%21sort%21SortPluginBase.php/group/views_sort_handlers/8.5.x **
 **                                                                          **
 ******************************************************************************/
    $data['scraped_content_field_data']['id'] = [
      'title' => $this->t('Scraped Content ID'),
      'help' => $this->t('A unique ID that is incremented automatically when a row is created'),
      'field' => [
        'id' => 'numeric',
      ],
      'argument' => [
        'id' => 'numeric',
      ],
      'filter' => [
        'id' => 'numeric',
      ],
      'sort' => [
        'id' => 'standard',
      ],
    ];

    $data['scraped_content_field_data']['headline'] = [
      'title' => $this->t('Headline'),
      'help' => $this->t('The headline that was assigned to the article'),
      'field' => [
        'id' => 'standard',
      ],
      'filter' => [
        'id' => 'string',
      ],
      'sort' => [
        'id' => 'standard',
      ],
    ];

    $data['scraped_content_field_data']['article_body'] = [
      'title' => $this->t('Body'),
      'help' => $this->t('The body that was assigned to the article'),
      'field' => [
        'id' => 'standard',
      ],
      'argument' => [
        'id' => 'string',
      ],
      'filter' => [
        'id' => 'string',
      ],
      'sort' => [
        'id' => 'standard',
      ],
    ];

/******************************************************************************
 **                                                                          **
 ** Creating a view that filters by this field will throw an error.          **
 ** @see: https://www.drupal.org/project/drupal/issues/2973172 and           **
 **       https://www.drupal.org/project/drupal/issues/2973807               **
 **                                                                          **
 ******************************************************************************/
    $data['scraped_content_field_data']['article_status'] = [
      'title' => $this->t('Status'),
      'help' => $this->t('The status of the article'),
      'field' => [
        'id' => 'standard',
      ],
      'argument' => [
        'id' => 'string_list_field',
      ],
      'filter' => [
        'id' => 'list_field',
      ],
      'sort' => [
        'id' => 'standard',
      ],
    ];

    $data['scraped_content_field_data']['editor'] = [
      'title' => $this->t('Editor'),
      'help' => $this->t('The editor of the article'),
      'field' => [
        'id' => 'user_data',
      ],
      'argument' => [
        'id' => 'user_uid',
      ],
      'filter' => [
        'id' => 'user_name',
      ],
      'sort' => [
        'id' => 'standard',
      ],
    ];

    $data['scraped_content_field_data']['source'] = [
      'title' => $this->t('Source'),
      'help' => $this->t('The source URL'),
      'field' => [
        'id' => 'standard',
      ],
      'filter' => [
        'id' => 'string',
      ],
      'sort' => [
        'id' => 'standard',
      ],
    ];

    $data['scraped_content_field_data']['post_date'] = [
      'title' => $this->t('Post Date'),
      'help' => $this->t('The date the original article was posted'),
      'field' => [
        'id' => 'standard',
      ],
      'filter' => [
        'id' => 'string',
      ],
      'sort' => [
        'id' => 'standard',
      ],
    ];

/******************************************************************************
 **                                                                          **
 ** Creating a view that filters by this field will throw an error.          **
 ** @see: https://www.drupal.org/project/drupal/issues/2973172 and           **
 **       https://www.drupal.org/project/drupal/issues/2973809               **
 **                                                                          **
 ******************************************************************************/
    $data['scraped_content_field_data']['created'] = [
      'title' => $this->t('Created'),
      'help' => $this->t('The time the entry was created'),
      'field' => [
        'id' => 'datetime',
      ],
    ];

/******************************************************************************
 **                                                                          **
 ** Creating a view that filters by this field will throw an error.          **
 ** @see: https://www.drupal.org/project/drupal/issues/2973172 and           **
 **       https://www.drupal.org/project/drupal/issues/2973809               **
 **                                                                          **
 ******************************************************************************/
    $data['scraped_content_field_data']['changed'] = [
      'title' => $this->t('Changed'),
      'help' => $this->t('The time the entry was updated'),
      'field' => [
        'id' => 'datetime',
      ],
    ];

    return $data;
  }

}
