<?php
namespace Drupal\sistema_votos;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

class OpcaoListBuilder extends EntityListBuilder {

  public function buildHeader() {
    $header['titulo'] = $this->t('Título da opção');
    $header['pergunta'] = $this->t('Pergunta associada');
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
  $row['total_votos'] = $entity->get('total_votos')->value;
  return $row + parent::buildRow($entity);
}

}
