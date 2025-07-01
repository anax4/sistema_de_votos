<?php

namespace Drupal\sistema_votos\Controller;

use Drupal\Core\Controller\ControllerBase;

class ResultadosController extends ControllerBase {

  public function resultados() {
    $perguntas = \Drupal::entityTypeManager()->getStorage('pergunta')->loadMultiple();
    $dados = [];
    $total_votos_geral = 0;

    // Monta estrutura com total de votos por pergunta
    foreach ($perguntas as $pergunta) {
      $opcoes = \Drupal::entityTypeManager()->getStorage('opcao')->loadByProperties([
        'pergunta' => $pergunta->id(),
      ]);

      $total_votos_pergunta = 0;
      foreach ($opcoes as $opcao) {
        $votos = $opcao->hasField('total_votos') ? ($opcao->get('total_votos')->value ?? 0) : 0;
        $total_votos_pergunta += $votos;
      }

      $dados[] = [
        'pergunta' => $pergunta->label(),
        'total_votos' => $total_votos_pergunta,
      ];

      $total_votos_geral += $total_votos_pergunta;
    }

    // Ordena do mais votado para o menos
    usort($dados, fn($a, $b) => $b['total_votos'] <=> $a['total_votos']);

    // Monta tabela HTML
    $html = '<h2>Resultados das Votações</h2>';
    $html .= '<table style="width:100%; border-collapse:collapse;" border="1">';
    $html .= '<tr>
      <th style="text-align:left;">Pergunta</th>
      <th style="text-align:center;">Total de votos</th>
      <th style="text-align:center;">%</th>
    </tr>';

    foreach ($dados as $item) {
      $percentual = ($total_votos_geral > 0)
        ? round(($item['total_votos'] / $total_votos_geral) * 100, 1)
        : 0;
      $html .= '<tr>
        <td>' . $item['pergunta'] . '</td>
        <td style="text-align:center;">' . $item['total_votos'] . '</td>
        <td style="text-align:center;">' . $percentual . '%</td>
      </tr>';
    }

    $html .= '</table>';

    return [
      '#markup' => $html,
    ];
  }

}
