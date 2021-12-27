<?php

namespace Drupal\oleg;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining an oleg entity.
 *
 * @ingroup oleg
 */
interface OlegInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
