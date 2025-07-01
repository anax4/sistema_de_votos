<?php

namespace Drupal\sistema_votos\Plugin\rest\resource;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\rest\Plugin\ResourceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\rest\ResourceResponse;

/**
 * endpoint para registrar votos.
 *
 * @RestResource(
 *   id = "voto_resource",
 *   label = @Translation("Voto Resource"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/voto",
 *     "https://www.drupal.org/link-relations/create" = "/api/v1/voto"
 *   }
 * )
 */
class VotoResource extends ResourceBase {

  protected $entityTypeManager;

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerChannelFactoryInterface $logger_factory,
    EntityTypeManagerInterface $entityTypeManager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger_factory->get('sistema_votos'));
    $this->entityTypeManager = $entityTypeManager;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory'),
      $container->get('entity_type.manager')
    );
  }

  public function post(Request $request): ResourceResponse {
    $data = json_decode($request->getContent(), true);

    if (empty($data['pergunta_id']) || empty($data['opcao_id'])) {
      throw new BadRequestHttpException('Os campos pergunta_id e opcao_id são obrigatórios.');
    }

    $pergunta_id = $data['pergunta_id'];
    $opcao_id = $data['opcao_id'];

    $opcao_storage = $this->entityTypeManager->getStorage('opcao');
    $opcao = $opcao_storage->load($opcao_id);

    if (!$opcao) {
      throw new BadRequestHttpException('A opção informada não existe.');
    }

    $opcao_pergunta_id = $opcao->get('pergunta')->target_id ?? null;
    if ($opcao_pergunta_id != $pergunta_id) {
      throw new BadRequestHttpException('A opção não pertence à pergunta informada.');
    }

    return new ResourceResponse([
      'status' => 'success',
      'message' => 'Voto registrado com sucesso.',
    ], 201);
  }
}
