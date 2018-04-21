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

    $data['scraped_content']['table']['group'] = $this->t('Scraped Content Table');
    $data['scraped_content']['table']['base'] = [
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
    $data['scraped_content']['id'] = [
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

    $data['scraped_content']['label'] = [
      'title' => $this->t('Scraped Content Label'),
      'help' => $this->t('The label that was assigned to this record'),
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

    $data['scraped_content']['ot_coordinates'] = [
      'title' => $this->t('Scraped Content Coordinates'),
      'help' => $this->t('The latitude and longitude where the measurement was taken'),
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

    $data['scraped_content']['ot_depth'] = [
      'title' => $this->t('Scraped Content Depth'),
      'help' => $this->t('The depth at which the measurement was taken'),
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

    $data['scraped_content']['ot_temperature'] = [
      'title' => $this->t('Scraped Content Temperature'),
      'help' => $this->t('The temperature that was reported'),
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

    $data['scraped_content']['ot_reported_date'] = [
      'title' => $this->t('Scraped Content Reported Date'),
      'help' => $this->t('The date that temperature that was reported'),
      'field' => [
        'id' => 'date',
      ],
      'argument' => [
        'id' => 'date',
      ],
      'filter' => [
        'id' => 'date',
      ],
      'sort' => [
        'id' => 'date',
      ],
    ];

    $data['scraped_content']['ot_reporter'] = [
      'title' => $this->t('Scraped Content Reporter'),
      'help' => $this->t('The name of the person or organization that reported the data'),
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

    return $data;
  }

}
