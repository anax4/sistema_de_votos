<?php

namespace Drupal\sistema_votos\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller para criar e editar Pergunta.
 */
class CadastroPerguntaForm extends ContentEntityForm {

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    return $form;
  }

  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $status = $entity->save();

    if ($status == SAVED_NEW) {
      $this->messenger()->addStatus($this->t('Pergunta %label criada.', ['%label' => $entity->label()]));
    }
    else {
      $this->messenger()->addStatus($this->t('Pergunta %label atualizada.', ['%label' => $entity->label()]));
    }

    $form_state->setRedirect('entity.pergunta.add_form');
  }

}
