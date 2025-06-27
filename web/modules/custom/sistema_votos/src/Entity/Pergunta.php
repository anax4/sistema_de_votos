<?php

namespace Drupal\sistema_votos\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
// use Drupal\Core\Entity\EntityOwnerTrait;


/**
 * Defines the Pergunta entity.
 *
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
 *     "access" = "Drupal\sistema_votos\PerguntaAccessControlHandler",
 *     "list_builder" = "Drupal\sistema_votos\PerguntaListBuilder",
 *     "form" = {
 *       "default" = "Drupal\sistema_votos\Form\PerguntaForm",
 *       "delete" = "Drupal\sistema_votos\Form\PerguntaDeleteForm"
 *     }
 *   },
 *   links = {
 *     "canonical" = "/pergunta/{pergunta}",
 *     "edit-form" = "/pergunta/{pergunta}/edit",
 *     "delete-form" = "/pergunta/{pergunta}/delete"
 *   },
 *   admin_permission = "administer pergunta entities"
 * )
 */
class Pergunta extends ContentEntityBase
{
    use EntityChangedTrait;
    use EntityPublishedTrait;
    // use EntityOwnerTrait;



    public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
    {
        $fields = parent::baseFieldDefinitions($entity_type);

        $fields['titulo'] = BaseFieldDefinition::create('string')
            ->setLabel(t('Título'))
            ->setRequired(TRUE)
            ->setTranslatable(TRUE)
            ->setSettings(['max_length' => 255])
            ->setDisplayOptions('form', [
                'type' => 'string_textfield',
                'weight' => -5,
            ]);

        $fields['identificador'] = BaseFieldDefinition::create('string')
            ->setLabel(t('Identificador único'))
            ->setRequired(TRUE)
            ->setSettings(['max_length' => 100])
            ->setDisplayOptions('form', [
                'type' => 'string_textfield',
                'weight' => -4,
            ]);

        $fields['mostra_resultado'] = BaseFieldDefinition::create('boolean')
            ->setLabel(t('Mostrar resultado'))
            ->setDefaultValue(TRUE)
            ->setDisplayOptions('form', [
                'type' => 'boolean_checkbox',
                'weight' => -3,
            ]);

        $fields['status'] = BaseFieldDefinition::create('boolean')
            ->setLabel(t('Ativo'))
            ->setDefaultValue(TRUE)
            ->setDisplayOptions('form', [
                'type' => 'boolean_checkbox',
                'weight' => -2,
            ]);

        $fields['criado'] = BaseFieldDefinition::create('created')
            ->setLabel(t('Criado em'));

        $fields['alterado'] = BaseFieldDefinition::create('changed')
            ->setLabel(t('Alterado em'));

        return $fields;
    }
}
