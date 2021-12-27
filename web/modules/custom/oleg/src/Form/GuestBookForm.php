<?php

namespace Drupal\oleg\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\MessageCommand;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the oleg entity edit forms.
 */
class GuestBookForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    /** @var \Drupal\oleg\Entity\GuestBook $entity */
    $form = parent::buildForm($form, $form_state);

    $form['user_name']['widget'][0]['value']['#prefix'] = '<div class="user__name-validation-message"></div>';
    $form['user_name']['widget'][0]['value']['#ajax'] = [
      'callback' => '::ajaxUserNameValidate',
      'disable-refocus' => TRUE,
      'event' => 'keyup',
      'progress' => [
        'type' => 'none',
      ],
    ];

    $form['user_email']['widget'][0]['value']['#prefix'] = '<div class="user__email-validation-message"></div>';
    $form['user_email']['widget'][0]['value']['#ajax'] = [
      'callback' => '::ajaxEmailValidate',
      'disable-refocus' => TRUE,
      'event' => 'keyup',
      'progress' => [
        'type' => 'none',
      ],
    ];

    $form['user_phone']['widget'][0]['value']['#prefix'] = '<div class="user__phone-validation-message"></div>';
    $form['user_phone']['widget'][0]['value']['#ajax'] = [
      'callback' => '::ajaxPhoneValidate',
      'disable-refocus' => TRUE,
      'event' => 'keyup',
      'progress' => [
        'type' => 'none',
      ],
    ];

    return $form;
  }

  /**
   * Show message when field user_name is valid or not by ajax.
   */
  public function ajaxUserNameValidate(array &$form, FormStateInterface $form_state): AjaxResponse {
    $response = new AjaxResponse();
    if (!preg_match('/^[_A-Za-z0-9- \\+]{2,100}$/', $form_state->getValue('user_name')[0]['value'])) {
      $response->addCommand(
        new MessageCommand(
          $this->t('Sorry, the name you entered is incorrect, please enter a valid name.'),
          '.user__name-validation-message', ['type' => 'error'],)
      );

    }
    else {
      $response->addCommand(new MessageCommand(
        $this->t('Your name is correct.'),
        '.user__name-validation-message', ['type' => 'status'])
      );
    }
    return $response;
  }

  /**
   * Show message when field user_email is valid or not by ajax.
   */
  public function ajaxEmailValidate(array &$form, FormStateInterface $form_state): AjaxResponse {
    $response  = new AjaxResponse();
    if (!preg_match('/^[_A-Za-z0-9-\\+]*@[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$/', $form_state->getValue('user_email')[0]['value'])) {
      $response->addCommand(
        new MessageCommand(
          $this->t('Sorry, the email you entered is incorrect, please enter a valid email.'),
          '.user__email-validation-message', ['type' => 'error'], TRUE)
      );
    }
    else {
      $response->addCommand(
        new MessageCommand(
          $this->t('Your email is correct.'),
          '.user__email-validation-message',['type' => 'status'], TRUE)
      );
    }
    return $response;
  }

  /**
   * Show message when field user_phone is valid or not by ajax.
   */
  public function ajaxPhoneValidate(array &$form, FormStateInterface $form_state): AjaxResponse {
    $userPhone = $form_state->getValue('user_phone')[0]['value'];
    $response = new AjaxResponse();
    if (!preg_match('/^((\\+)|(00))[0-9]{12}$/', $userPhone)) {
      $response->addCommand(new MessageCommand(
        $this->t('Sorry, the phone you entered is incorrect, please enter a valid phone number.'),
        '.user__phone-validation-message', ['type' => 'error'], TRUE));
    }
    else {
      $response->addCommand(new MessageCommand(
          $this->t('Your telephone number is correct.'),
          '.user__phone-validation-message', ['type' => 'status'], TRUE)
      );
    }
    return $response;
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Core\Entity\EntityMalformedException
   */
  public function save(array $form, FormStateInterface $form_state) {
    $form = parent::save($form, $form_state);

    $entity = $this->entity;
    if ($form == SAVED_UPDATED) {
      $this->messenger()
        ->addMessage($this->t('The message %feed has been updated.', ['%feed' => $entity->toLink()->toString()]));
    }
    else {
      $this->messenger()
        ->addMessage($this->t('%feed, your message has been added.', ['%feed' => $entity->toLink()->toString()]));
    }

    $form_state->setRedirectUrl($this->entity->toUrl('page'));
    return $form;
  }

}
