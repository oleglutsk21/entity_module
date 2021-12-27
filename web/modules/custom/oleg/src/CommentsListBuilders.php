<?php

namespace Drupal\oleg;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for guest_book_review entity.
 */
class CommentsListBuilders extends EntityListBuilder {

  /**
   * {@inheritdoc}
   *
   * Build the header and content lines for the review list.
   */
  public function buildHeader() {
    $header['id'] = $this->t('ReviewID');
    $header['user_name'] = $this->t('User name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['id'] = $entity->id();
    $row['user_name'] = $entity->toLink()->toString();
    return $row + parent::buildRow($entity);
  }

}
