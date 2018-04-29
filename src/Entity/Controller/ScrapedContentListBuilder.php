<?php

namespace Drupal\web_scraper\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/******************************************************************************
 **                                                                          **
 ** We are leveraging some basic functionality that the Entity API provides  **
 ** us. It will give us a functional interface, although it will be lacking  **
 ** in some regards. We could have done more work directly in this builder   **
 ** like the node and user core modules did.                                 **
 ** @see: core/modules/node/src/NodeListBuilder                              **
 **       core/modules/user/src/UserListBuilder                              **
 **                                                                          **
 ******************************************************************************/

/**
 * Provides a list controller for IAI Ocean Temperature entities.
 */
class ScrapedContentListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['headline'] = $this->t('Headline');
    $header['article_status'] = $this->t('Status');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['headline'] = $entity->headline->value;
    $row['article_status'] = $entity->article_status->value;
    return $row + parent::buildRow($entity);
  }

}
