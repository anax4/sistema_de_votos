<?php

namespace Drupal\sistema_votos\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Defines the Opcao entity.
 *
 * @ContentEntityType(
 *   id = "opcao",
 *   label = @Translation("Opção"),
 *   base_table = "opcao",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "label" = "titulo",
 *     "status" = "status"
 *   },
 *   admin_permission = "administer opcao entity",
 *   handlers = {
 *     "list_builder" = "Drupal\sistema_votos\OpcaoListBuilder",
 *     "form" = {
 *       "default" = "Drupal\sistema_votos\Form\OpcaoForm",
 *       "add" = "Drupal\sistema_votos\Form\OpcaoForm",
 *       "edit" = "Drupal\sistema_votos\Form\OpcaoForm",
 *       "delete" = "Drupal\sistema_votos\Form\OpcaoDeleteForm"
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider"
 *     }
 *   },
 *   links = {
 *     "canonical" = "/opcao/{opcao}",
 *     "add-form" = "/admin/opcao/add",
 *     "edit-form" = "/admin/opcao/{opcao}/edit",
 *     "delete-form" = "/admin/opcao/{opcao}/delete",
 *     "collection" = "/admin/opcao"
 *   }
 * )
 */
class Opcao extends ContentEntityBase {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    
        $fields['pergunta'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Pergunta associada'))
      ->setDescription(t('Escolha a pergunta à qual esta opção está ligada.'))
      ->setSetting('target_type', 'pergunta')
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => -2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
   ->setDefaultValueCallback('\Drupal\sistema_votos\Entity\Opcao::defaultPergunta');




    $fields['titulo'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Título'))
      ->setRequired(TRUE)
      ->setSettings(['max_length' => 255])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ]);

    $fields['descricao'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Descrição'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => -4,
      ]);

    $fields['imagem'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Imagem da opção'))
      ->setSettings([
        'file_extensions' => 'png jpg jpeg',
        'alt_field_required' => FALSE,
      ])
      ->setDisplayOptions('form', [
        'type' => 'image_image',
        'weight' => -3,
      ]);

    $fields['total_votos'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Total de votos'))
      ->setDefaultValue(0)
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Ativo'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 0,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Criado em'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Modificado em'));

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
public function label() {
  if ($this->get('pergunta')->entity) {
    return $this->get('pergunta')->entity->label();
  }
  return $this->get('titulo')->value;
}


public static function defaultPergunta($entity, $context) {
  $query = \Drupal::entityTypeManager()->getStorage('pergunta')->getQuery()
    ->accessCheck(FALSE) 
    ->sort('criado', 'ASC')
    ->range(0, 1);
  $ids = $query->execute();

  return $ids ? reset($ids) : NULL;
}

}
