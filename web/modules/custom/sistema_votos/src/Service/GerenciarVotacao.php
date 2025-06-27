<?php

namespace Drupal\sistema_votos\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\sistema_votos\Entity\Pergunta;
use Drupal\sistema_votos\Entity\Opcao;


class GerenciarVotacao {

  protected $entityTypeManager;
  protected $configFactory;

  public function __construct(EntityTypeManagerInterface $entityTypeManager, ConfigFactoryInterface $configFactory) {
    $this->entityTypeManager = $entityTypeManager;
    $this->configFactory = $configFactory;
  }

  /**
   *
   * @param int $pergunta_id
   * @param int $opcao_id
   *
   * @return bool
   */
  public function votar($pergunta_id, $opcao_id) {
    // Verifica se o sistema de votação está habilitado.
    $config = $this->configFactory->get('sistema_votos.config');
    if (!$config->get('votacao_ativa')) {
      return FALSE;
    }

    // Carrega pergunta e opção.
    /** @var \Drupal\sistema_votos\Entity\Pergunta $pergunta */
    $pergunta = $this->entityTypeManager->getStorage('pergunta')->load($pergunta_id);
    /** @var \Drupal\sistema_votos\Entity\Opcao $opcao */
    $opcao = $this->entityTypeManager->getStorage('opcao')->load($opcao_id);

    // Verifica se existe.
    if (!$pergunta || !$opcao) {
      return FALSE;
    }

    // Valida se a opção pertence à pergunta.
    if ($opcao->get('pergunta')->target_id != $pergunta_id) {
      return FALSE;
    }

    // Incrementa o contador de votos.
    $total = (int) $opcao->get('total_votos')->value;
    $opcao->set('total_votos', $total + 1);
    $opcao->save();

    return TRUE;
  }

}
