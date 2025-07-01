<?php

namespace Drupal\sistema_votos\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;

class DashboardController extends ControllerBase {

  public function content() {
    $items = [
      Link::fromTextAndUrl('➕ Cadastrar Pergunta', Url::fromRoute('entity.pergunta.add_form')),
      Link::fromTextAndUrl('➕ Cadastrar Opção de Resposta', Url::fromRoute('entity.opcao.add_form')),
      Link::fromTextAndUrl('📊 Ver Resultados das Votações', Url::fromRoute('sistema_votos.resultados')),
      Link::fromTextAndUrl('🗳️ Acessar Votação', Url::fromUri('internal:/votacao')),
    ];

    return [
      '#title' => $this->t('Sistema de Votos'),
      '#theme' => 'item_list',
      '#items' => $items,
    ];
  }

}
