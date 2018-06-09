<?php

namespace Drupal\web_scraper\Form;

use Drupal\Core\Entity\ContentEntityDeleteForm;

/******************************************************************************
 **                                                                          **
 ** For more information about the delete form                               **
 ** @see: https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Entity%21entity.api.php/group/entity_api/8.5.x **
 ** paying attention to the section about the delete form handler class.     **
 ** We have used ContentEntityDeleteForm and patterned our approach after    **
 ** what Node does so that multilingual works properly. If we extend a       **
 ** different class, then we need to make sure that we write a submitForm    **
 ** method that properly handles translations.                               **
 **                                                                          **
 ******************************************************************************/

/**
 * Provides a form for deleting a Scraped Content entity.
 */
class ScrapedContentDeleteForm extends ContentEntityDeleteForm {

  /**
   * {@inheritdoc}
   */
  protected function getDeletionMessage() {
    $entity = $this->getEntity();

    if (!$entity->isDefaultTranslation()) {
      return $this->t('@language translation of the Scraped Content entry %label has been deleted.', [
        '@language' => $entity->language()->getName(),
        '%label' => $entity->label(),
      ]);
    }

    return $this->t('The Scraped Content entry %label has been deleted.', [
      '%label' => $entity->label(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  protected function logDeletionMessage() {
    $entity = $this->getEntity();
    $this->logger('content')->notice('Scraped Content: deleted entry %label.', ['%label' => $entity->label()]);
  }

}
