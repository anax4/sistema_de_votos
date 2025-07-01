<?php

namespace Drupal\sistema_votos\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;

class DashboardController extends ControllerBase {

  public function content() {
    $items = [
      Link::fromTextAndUrl('➕ Cadastrar Pergunta', Url::fromRoute('entity.pergunta.add_form'))->toString(),
      Link::fromTextAndUrl('➕ Cadastrar Opção de Resposta', Url::fromRoute('entity.opcao.add_form'))->toString(),
    ];

    $list = '<ul><li>' . implode('</li><li>', $items) . '</li></ul>';

    return [
      '#markup' => '<h2>Sistema de Votos</h2>' . $list,
    ];
  }

}
