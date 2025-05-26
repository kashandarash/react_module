<?php

namespace Drupal\react_module\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the todo item entity edit forms.
 */
class ReactModuleTodoItemForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);

    $entity = $this->getEntity();

    $message_arguments = ['%label' => $entity->toLink()->toString()];
    $logger_arguments = [
      '%label' => $entity->label(),
      'link' => $entity->toLink($this->t('View'))->toString(),
    ];

    switch ($result) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('New todo item %label has been created.', $message_arguments));
        $this->logger('react_module')->notice('Created new todo item %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The todo item %label has been updated.', $message_arguments));
        $this->logger('react_module')->notice('Updated todo item %label.', $logger_arguments);
        break;
    }

    $form_state->setRedirect('entity.react_module_todo_item.collection');

    return $result;
  }

}
