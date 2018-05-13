<?php

namespace Drupal\web_scraper\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\UserInterface;
use Drupal\web_scraper\ScrapedContentInterface;

/******************************************************************************
 **                                                                          **
 ** For more details on how we define our content entity                     **
 ** @see: https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Entity%21entity.api.php/group/entity_api/8.5.x **
 **       https://www.drupal.org/docs/8/api/entity-api/introduction-to-entity-api-in-drupal-8 **
 **       https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Entity%21EntityType.php/class/EntityType/8.5.x **
 **                                                                          **
 ** Here are some details about some of the choices we have made:            **
 ** translatable = TRUE                                                      **
 **   We want it to be possible to translate the fields in the data records. **
 **   For instance, we want to be able to express the depth and the          **
 **   temperature in different units of measure.                             **
 ** base_table, data_table                                                   **
 **   Because we have made our entity translatable Drupal will use two       **
 **   tables. We will use the default table names:                           **
 **   iai_ocean_temperature and iai_ocean_temperature_field_data             **
 **   If we had not made our entity translatable Drupal would have put       **
 **   everything in a single table: iai_ocean_temperature.                   **
 **   The User entity makes use of these attributes so that it may specify   **
 **   a different name for the two tables.                                   **
 **   @see: core/modules/user/src/Entity/User                                **
 ** fieldable = FALSE                                                        **
 **   We have decided that we want to maintain strict control over this      **
 **   entity and not allow people to add fields to it through the user       **
 **   interface. (Note: If we wanted it to be fieldable we would have had to **
 **   add the field_ui_base_route attribute in the annotation.)              **
 **                                                                          **
 ** We haven't defined the list_cache_tags key. Instead we are allowing      **
 ** core/lib/Drupal/Core/Entity/EntityType.php to default it for us, which   **
 ** will result in a tag of scraped_content_list being created.              **
 ** @see \Drupal\Core\Entity\EntityType::__construct                         **
 ******************************************************************************/

/**
 * Defines the Scraped Content entity.
 *
 * @ContentEntityType(
 *   id = "scraped_content",
 *   label = @Translation("Scraped Content"),
 *   handlers = {
 *     "access" = "Drupal\web_scraper\ScrapedContentAccessControlHandler",
 *     "form" = {
 *       "default" = "Drupal\web_scraper\Form\ScrapedContentForm",
 *       "delete" = "Drupal\web_scraper\Form\ScrapedContentDeleteForm",
 *     },
 *     "list_builder" = "Drupal\web_scraper\Entity\Controller\ScrapedContentListBuilder",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\web_scraper\ScrapedContentViewsData"
 *   },
 *   translatable = TRUE,
 *   base_table = "scraped_content",
 *   data_table = "scraped_content_field_data",
 *   fieldable = FALSE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "headline",
 *     "langcode" = "langcode"
 *   },
 *   links = {
 *     "canonical" = "/scraped_content/{scraped_content}",
 *     "edit-form" = "/scraped_content/{scraped_content}/edit",
 *     "delete-form" = "/scraped_content/{scraped_content}/delete",
 *     "collection" = "/scraped_content/list"
 *   }
 * )
 */
class ScrapedContent extends ContentEntityBase implements ScrapedContentInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function getArticleBody() {
    return $this->get('article_body')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getArticleStatus() {
    return $this->get('article_status')->value;
  }

/******************************************************************************
 **                                                                          **
 ** The EntityChangedTrait already provides the method for getChangedTime.   **
 **                                                                          **
 ******************************************************************************/

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * Default value callback for 'editor' base field definition.
   *
   * @see ::baseFieldDefinitions()
   *
   * @return int[]
   *   An array of default values.
   */
  public static function getCurrentUserId() {
    return [\Drupal::currentUser()->id()];
  }

  /**
   * {@inheritdoc}
   */
  public function getEditor() {
    return $this->get('editor')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getEditorId() {
    return $this->get('editor')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function getHeadline() {
    return $this->get('headline')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getScrapedContent() {
    return $this->get('scraped_content')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getScrapedH1() {
    return $this->get('scraped_h1')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getScrapedTitle() {
    return $this->get('scraped_title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getSource() {
    return $this->get('source')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setArticleBody($body_text) {
    $this->set('article_body', $body_text);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setArticleStatus($status) {
    $this->set('article_status', $status);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setEditor(UserInterface $account) {
    $this->set('editor', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setEditorId($uid) {
    $this->set('editor', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setHeadline($headline) {
    $this->set('headline', $headline);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

/******************************************************************************
 **                                                                          **
 ** We start out by retrieving the base field definitions from               **
 ** ContentEntityBase. Since we've used the "id" and "langcode" attributes   **
 ** in entity_keys, these two fields will be defined for us.                 **
 ** @see: core/lib/Drupal/Core/Entity/ContentEntityBase                      **
 **                                                                          **
 ******************************************************************************/
    $fields = parent::baseFieldDefinitions($entity_type);

/******************************************************************************
 **                                                                          **
 ** We define the rest of the fields for our entity below.                   **
 ** @see: https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Field%21BaseFieldDefinition.php/class/BaseFieldDefinition/8.5.x **
 **                                                                          **
 ** For the definitions of possible field types                              **
 ** @see: core/lib/Drupal/Core/Field/Plugin/Field/FieldType                  **
 ** We are choosing to use the default_widget and default_formatter as       **
 ** specified by the field types.                                            **
 **                                                                          **
 ** We must at least set the 'weight' attribute display option.              **
 ** @see: https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Field%21BaseFieldDefinition.php/function/BaseFieldDefinition%3A%3AsetDisplayOptions/8.5.x **
 ** For a list of other display options                                      **
 ** @see: https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Field%21FieldDefinitionInterface.php/function/FieldDefinitionInterface%3A%3AgetDisplayOptions/8.5.x **
 **                                                                          **
 ** If we wanted to use a different formatter we could specify the 'type'    **
 ** attribute for the display options for 'form'.                            **
 ** @see: core/lib/Drupal/Core/Field/Plugin/Field/FieldFormatter             **
 **                                                                          **
 ** If we wanted to use a different widget we could specify the 'type'       **
 ** attribute for the display options for 'view'.                            **
 ** @see: core/lib/Drupal/Core/Field/Plugin/Field/FieldWidget                **
 **                                                                          **
 ******************************************************************************/

    $fields['headline'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Headline'))
      ->setDescription(t('The editor-entered headline for the article'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'weight' => 1,
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'weight' => 1,
      ]);

    $fields['article_body'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Article Body'))
      ->setDescription(t('The editor-entered body for the article'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setSetting('text_processing', 0)
      ->setDisplayOptions('form', [
        'weight' => 2,
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'weight' => 2,
      ]);

    $fields['source'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Source'))
      ->setDescription(t('The URL that was scraped'))
      ->setRequired(TRUE)
      ->setTranslatable(FALSE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'weight' => 7,
      ])
      ->setDisplayOptions('view', [
        'weight' => 7,
      ]);

/******************************************************************************
 **                                                                          **
 ** We don't want to see any of the other fields when we are viewing the     **
 ** entity, so we won't set the display options for 'view'.                  **
 **                                                                          **
 ******************************************************************************/

/******************************************************************************
 **                                                                          **
 ** @see: core/modules/node/src/Entity/Node.php                              **
 **       core/modules/media/src/Entity/Media.php                            **
 **                                                                          **
 ******************************************************************************/
    $fields['editor'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Editor'))
      ->setDescription(t('The editor of the article'))
      ->setDefaultValueCallback(static::class . '::getCurrentUserId')
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setSetting('target_type', 'user')
      ->setDisplayOptions('form', [
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => '',
        ],
        'type' => 'entity_reference_autocomplete',
        'weight' => 3,
      ]);

    $fields['article_status'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Article Status'))
      ->setDescription(t('The status of the article'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setSetting('max_length', 255)
      ->setSetting('allowed_values', ['scraped' => 'Scraped from Source', 'translated' => 'Translation submitted for review', 'published' => 'Ready for use', 'rejected' => 'Not usable'])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 4,
      ]);

    $fields['scraped_h1'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Scraped h1'))
      ->setDescription(t('The h1 tag that was scraped from the source'))
      ->setRequired(TRUE)
      ->setTranslatable(FALSE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'weight' => 5,
      ]);

    $fields['scraped_title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Scraped title'))
      ->setDescription(t('The title tag that was scraped from the source'))
      ->setRequired(TRUE)
      ->setTranslatable(FALSE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'weight' => 5,
      ]);

    $fields['scraped_content'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Scraped Content'))
      ->setDescription(t('The content that was scraped from the source'))
      ->setRequired(TRUE)
      ->setTranslatable(FALSE)
      ->setSetting('text_processing', 0)
      ->setDisplayOptions('form', [
        'weight' => 6,
      ]);

    $fields['changed'] = BaseFieldDefinition::create('changed');
    $fields['created'] = BaseFieldDefinition::create('created');

    return $fields;
  }
}
