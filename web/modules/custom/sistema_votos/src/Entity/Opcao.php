<?php

namespace Drupal\sistema_votos\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityChangedTrait;

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
 *     "list_builder" = "Drupal\Core\Entity\EntityListBuilder",
 *     "form" = {
 *       "add" = "Drupal\Core\Entity\ContentEntityForm",
 *       "edit" = "Drupal\Core\Entity\ContentEntityForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     }
 *   },
 *   links = {
 *     "canonical" = "/opcao/{opcao}",
 *     "edit-form" = "/admin/opcao/{opcao}/edit",
 *     "delete-form" = "/admin/opcao/{opcao}/delete",
 *     "collection" = "/admin/opcao"
 *   }
 * )
 */
class Opcao extends ContentEntityBase {

  use EntityChangedTrait;

  /**
   * Define os campos base da entidade.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

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

    $fields['pergunta'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Pergunta associada'))
      ->setSetting('target_type', 'pergunta')
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => -2,
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

}
