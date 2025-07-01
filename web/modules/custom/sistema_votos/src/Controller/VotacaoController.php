<?php

namespace Drupal\sistema_votos\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Symfony\Component\HttpFoundation\Request;

class VotacaoController extends ControllerBase {

  public function paginaVotacao() {
    $perguntas = \Drupal::entityTypeManager()->getStorage('pergunta')->loadMultiple();
    $links = [];

    foreach ($perguntas as $pergunta) {
    if ($pergunta->get('status')->value) {
          $titulo = $pergunta->label();
          $url = Url::fromRoute('sistema_votos.votacao_pergunta', ['pergunta_id' => $pergunta->id()]);
          $links[] = Link::fromTextAndUrl($titulo, $url);
        }
    }

    return [
      '#title' => $this->t('Participe da votação'),
      '#theme' => 'item_list',
      '#items' => $links,
    ];
  }

public function processaVoto(Request $request) {
  $pergunta_id = $request->request->get('pergunta_id');
  $opcao_id = $request->request->get('opcao_id');

  if ($pergunta_id && $opcao_id) {
    // Incrementa o total de votos
    $opcao = \Drupal::entityTypeManager()->getStorage('opcao')->load($opcao_id);

    if ($opcao && $opcao->hasField('total_votos')) {
      $votos_atual = $opcao->get('total_votos')->value ?? 0;
      $opcao->set('total_votos', $votos_atual + 1);
      $opcao->save();
    }

    // Atualiza sessão para não votar de novo
    $session = $request->getSession();
    if (!$session->isStarted()) {
      $session->start();
    }
    $votadas = $session->get('perguntas_votadas', []);
    $votadas[] = $pergunta_id;
    $session->set('perguntas_votadas', array_unique($votadas));

    $mensagem = $this->t('Voto computado com sucesso!');
  } else {
    $mensagem = $this->t('Não foi possível registrar seu voto.');
  }

  return [
    '#markup' => '<p>' . $mensagem . '</p><p><a href="/votacao">Voltar</a></p>',
  ];
}

public function votarPergunta($pergunta_id) {
  $pergunta_entity = \Drupal::entityTypeManager()->getStorage('pergunta')->load($pergunta_id);

  if (!$pergunta_entity) {
    throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
  }

  // O campo é 'pergunta'
  $opcoes = \Drupal::entityTypeManager()->getStorage('opcao')->loadByProperties([
    'pergunta' => $pergunta_id,
  ]);

  return [
    '#theme' => 'votacao_perguntas',
    '#pergunta' => $pergunta_entity,
    '#opcoes' => !empty($opcoes) ? $opcoes : [],
  ];
}

  public function getPerguntaTitle($pergunta_id) {
    $pergunta = \Drupal::entityTypeManager()->getStorage('pergunta')->load($pergunta_id);
    return $pergunta ? $pergunta->label() : $this->t('Pergunta');
  }

}
