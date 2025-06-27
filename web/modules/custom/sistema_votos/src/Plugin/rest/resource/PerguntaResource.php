<?php

namespace Drupal\sistema_votos\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Psr\Log\LoggerInterface;

/**
 *
 * @RestResource(
 *   id = "pergunta_resource",
 *   label = @Translation("Perguntas de Votação"),
 *   uri_paths = {
 *     "canonical" = "/api/perguntas"
 *   }
 * )
 */
class PerguntaResource extends ResourceBase
{

    protected EntityTypeManagerInterface $entityTypeManager;

    public function __construct(
        array $configuration,
        $plugin_id,
        $plugin_definition,
        array $serializer_formats,
        LoggerInterface $logger,
        EntityTypeManagerInterface $entityTypeManager
    ) {
        parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
        $this->entityTypeManager = $entityTypeManager;
    }

    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static
    {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->getParameter('serializer.formats'),
            $container->get('logger.factory')->get('sistema_votos'),
            $container->get('entity_type.manager')
        );
    }

    public function get(): ResourceResponse
    {
        $this->logger->info('Recurso REST chamado com sucesso.');
        $perguntas = $this->entityTypeManager->getStorage('pergunta')->loadMultiple();
        $dados = [];

        foreach ($perguntas as $pergunta) {
            if (!$pergunta->isPublished()) {
                continue;
            }

            $dados[] = [
                'id' => $pergunta->id(),
                'titulo' => $pergunta->get('titulo')->value ?? '',
                'identificador' => $pergunta->get('identificador')->value ?? '',
                'mostra_resultado' => $pergunta->get('mostra_resultado')->value ?? '',
            ];
        }

        return new ResourceResponse($dados, 200);
    }
}
