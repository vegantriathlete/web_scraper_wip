<?php

namespace Drupal\web_scraper;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/******************************************************************************
 **                                                                          **
 ** For more information about the access control handler                    **
 ** @see: https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Entity%21entity.api.php/group/entity_api/8.5.x **
 ** paying attention to the section about the access handler class           **
 **                                                                          **
 ** You can find a similar example at                                        **
 ** core/modules/taxonomy/src/TermAccessControlHandler                       **
 **                                                                          **
 ******************************************************************************/

/**
 * Determines access to IAI Ocean Temperature entities
 */
class ScrapedContentAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view scraped_content entity');
      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit scraped_content entity');
      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete scraped_content entity');
      default:
        // No opinion.
        return AccessResult::neutral();
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add scraped_content entity');
  }

}
