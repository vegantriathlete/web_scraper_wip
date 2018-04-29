<?php

namespace Drupal\web_scraper\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/******************************************************************************
 **                                                                          **
 ** For more information about the default form                              **
 ** @see: https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Entity%21entity.api.php/group/entity_api/8.5.x **
 ** paying attention to the section about the default form handler class. We **
 ** are creating a simple form. You may find more involved examples in the   **
 ** node, taxonomy and user modules.                                         **
 ** @see: core/modules/node/src/NodeForm                                     **
 **       core/modules/taxonomy/src/TermForm                                 **
 **       core/modules/user/src/ProfileForm                                  **
 **                                                                          **
 ******************************************************************************/

/**
 * Manages add and edit forms for Scraped Content entity
 */
class ScrapedContentForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $entity->save();
    $form_state->setRedirect('entity.scraped_content.collection');
  }

}
