<?php

namespace Drupal\sistema_votos\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;

/**
 * @ContentEntityType(
 *   id = "pergunta",
 *   label = @Translation("Pergunta"),
 *   base_table = "pergunta",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "label" = "title",
 *     "owner" = "uid"
 *   },
 *   handlers = {
 *     "list_builder" = "Drupal\sistema_votos\PerguntaListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "default" = "Drupal\sistema_votos\Form\CadastroPerguntaForm",
 *       "add" = "Drupal\sistema_votos\Form\CadastroPerguntaForm",
 *       "edit" = "Drupal\sistema_votos\Form\CadastroPerguntaForm",
 *       "delete" = "Drupal\sistema_votos\Form\PerguntaDeleteForm"
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider"
 *     }
 *   },
 *   admin_permission = "administer pergunta entities",
 *   links = {
 *     "canonical" = "/pergunta/{pergunta}",
 *     "edit-form" = "/pergunta/{pergunta}/edit",
 *     "delete-form" = "/pergunta/{pergunta}/delete",
 *     "add-form" = "/pergunta/add",
 *     "collection" = "/admin/content/perguntas"
 *   }
 * )
 */
class Pergunta extends ContentEntityBase {
  use EntityChangedTrait;
  use EntityPublishedTrait;

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['titulo'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Título'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setSettings(['max_length' => 255])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['identificador'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Identificador único'))
      ->setRequired(TRUE)
      ->setSettings(['max_length' => 100])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['mostra_resultado'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Mostrar resultado'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'boolean',
        'weight' => -3,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Ativo'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -2,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'boolean',
        'weight' => -2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['criado'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Criado em'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['alterado'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Alterado em'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

  public function label() {
  return $this->get('titulo')->value ?? ('Pergunta #' . $this->id());
}

}
