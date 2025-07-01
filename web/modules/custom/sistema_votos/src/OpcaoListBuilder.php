<?php
namespace Drupal\sistema_votos;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;
use Drupal\Core\Url;

class OpcaoListBuilder extends EntityListBuilder {

  public function buildHeader() {
    $header['titulo'] = $this->t('Título da opção');
    $header['pergunta'] = $this->t('Pergunta associada');
    $header['total_votos'] = $this->t('Total de votos');
    $header += parent::buildHeader();
    return $header;
  }

  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\sistema_votos\Entity\Opcao $entity */
    $row['titulo'] = $entity->label();
    $row['pergunta'] = '-';
    if ($entity->get('pergunta')->entity) {
      $row['pergunta'] = $entity->get('pergunta')->entity->label();
    }
    $row['total_votos'] = $entity->get('total_votos')->value ?? 0;
    return $row + parent::buildRow($entity);
  }

  public function render() {
    // Cria o botão acima da lista
    $build['add_link'] = [
      '#type' => 'link',
      '#title' => $this->t('➕ Adicionar nova opção'),
      '#url' => Url::fromRoute('entity.opcao.add_form'),
      '#attributes' => [
        'class' => ['button', 'button--primary'],
        'style' => 'margin-bottom: 1em;'
      ],
    ];

    // Renderiza a tabela padrão
    $build += parent::render();

    return $build;
  }
}
