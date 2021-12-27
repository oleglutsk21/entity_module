<?php

namespace Drupal\oleg\Entity;

use Drupal\Core\Entity\Annotation\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Defines the GuestBookComments entity.
 *
 * @ingroup oleg
 *
 * @ContentEntityType(
 *   id = "guest_book",
 *   label = @Translation("Guest book entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\oleg\Controller\GuestBookController",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\oleg\Form\GuestBookForm",
 *       "edit" = "Drupal\oleg\Form\GuestBookForm",
 *       "delete" = "Drupal\oleg\Form\GuestBookDeleteForm",
 *     },
 *     "access" = "Drupal\oleg\GuestBookAccessControlHandler",
 *   },
 *   base_table = "guest_book",
 *   admin_permission = "administer guest_book entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "label" = "user_name",
 *   },
 *   links = {
 *     "page" = "/guest-book",
 *     "canonical" = "/guest-book/{guest_book}",
 *     "edit-form" = "/guest-book/{guest_book}/edit",
 *     "delete-form" = "/comment/{guest_book}/delete",
 *     "collection" = "/guest-book/list"
 *   },
 *   field_ui_base_route = "guest_book.review_settings",
 * )
 */
class GuestBook extends ContentEntityBase implements ContentEntityInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Guest book entity.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Guest book entity.'))
      ->setReadOnly(TRUE);

    $fields['user_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Your name:'))
      ->setDescription(t('Write your name'))
      ->setSettings([
        'default_value' => NULL,
        'max_length' => 100,
        'text_processing' => 0,
      ])
      ->setPropertyConstraints('value', [
        'Regex' => [
          'pattern' => '/^[_A-Za-z0-9- \\+]{2,100}$/',
          'message' => t('The name must be between 2 and 100 characters long'),
        ],
        'Length' => [
          'min' => 2,
          'max' => 100,
        ],
      ])
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['user_email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Your email:'))
      ->setDescription(t('Write your email e.g.: example@example.ex'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 225,
        'text_processing' => 0,
      ])
      ->setPropertyConstraints('value', [
        'Regex' => [
          'pattern' => '/^[_A-Za-z0-9-\\+]*@[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$/',
          'message' => t('Email should look like this: example@example.ex'),
        ],
      ])
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'email_mailto',
        'weight' => 1,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['user_phone'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Your phone:'))
      ->setDescription(t('Write your telephone number e.g.: +999999999999'))
      ->setSettings([
        'default_value' => NULL,
        'max_length' => 13,
        'text_processing' => 0,
      ])
      ->setPropertyConstraints('value', [
        'Regex' => [
          'pattern' => '/^((\\+)|(00))[0-9]{12}$/',
          'message' => t('Telephone number should look like this: +999999999999'),
        ],
      ])
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'telephone_link',
        'weight' => 2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'telephone_default',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['user_message'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Your message:'))
      ->setDescription(t('Write your message'))
      ->setSettings([
        'default_value' => '',
        'text_processing' => 0,
      ])
      ->setPropertyConstraints('value', [
        'Length' => [
          'max' => 2000,
        ],
      ])
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string_textarea',
        'weight' => 3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 3,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['user_avatar'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Your avatar:'))
      ->setDescription(t('Avatar should be less than 2 MB and in JPEG, JPG or PNG format.'))
      ->setSettings([
        'file_directory' => 'guest_book/avatar/',
        'alt_field_required' => FALSE,
        'alt_field' => FALSE,
        'file_extensions' => 'png jpg jpeg',
        'max_filesize' => 2097152,
        'default_value' => NULL,
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'image',
        'weight' => 4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'image',
        'weight' => 4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['user_image'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Your image:'))
      ->setDescription(t('Image should be less than 5 MB and in JPEG, JPG or PNG format.'))
      ->setSettings([
        'file_directory' => 'guest_book/images/',
        'alt_field_required' => FALSE,
        'alt_field' => FALSE,
        'file_extensions' => 'png jpg jpeg',
        'max_filesize' => 5242880,
        'default_value' => NULL,
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'image',
        'weight' => 5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'image',
        'weight' => 5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'))
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'datetime_custom',
        'settings' => [
          'data_format' => 'm/d/Y H:i:s',
        ],
      ]);

    return $fields;
  }

}
