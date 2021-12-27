<?php

namespace Drupal\oleg\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class that control displaying form.
 */
class GuestBookController extends ControllerBase {

  /**
   * Drupal services.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityManager;

  /**
   * Drupal services.
   *
   * @var \Drupal\Core\Entity\EntityFormBuilder
   */
  protected $formBuild;

  /**
   * Method provide dependency injection and add services.
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->entityManager = $container->get('entity_type.manager');
    $instance->formBuild = $container->get('entity.form_builder');
    return $instance;
  }

  /**
   * Method that create output of module.
   */
  public function showComments(): array {

    $comments = [];

    $storage = \Drupal::entityTypeManager()->getStorage('guest_book');
    $entity     = $storage->create();
    $form    = $this->entityFormBuilder()->getForm($entity, 'add');
    $entity_id  = $storage->getQuery()
      ->sort('created', 'DESC')
      ->pager(5, 0)
      ->execute();

    $view    = \Drupal::entityTypeManager()->getViewBuilder('guest_book');
    $reviews = $storage->loadMultiple($entity_id);

    foreach ($reviews as $item) {
      $comments[] = $view->view($item);
    }

    return [
      '#theme'            => 'guest_book_page',
      '#form'             => $form,
      '#comments'           => $comments,
      '#contextual_links' => [
        'guest_book' => [
          'route_parameters' => ['guest_book' => $entity->id()],
        ],
      ],
      '#pager'            => [
        '#type' => 'pager',
      ],
    ];
  }

}
