<?php

namespace Drupal\core_neighborhood\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\core_neighborhood\Form\ProposalForm;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for rendering the custom form.
 */
class ProposalController extends ControllerBase {

  /**
   * Render the custom form.
   *
   * @return array
   *   A render array containing the custom form.
   */
  public function renderForm() {
    // Obtener el formulario
    $form = \Drupal::formBuilder()->getForm(ProposalForm::class);
    return [
      '#theme' => 'proposal_form',
      '#form' => $form,
    ];
  }

  public function getClasifications($parent) : JsonResponse {

    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('activity_clasification');
    $options = [];
    if($parent != -1){
        foreach ($terms as $term) {
          if(isset($term->parents[0]) && $term->parents[0] == $parent)
            $options[] =[
              "id" => $term->tid,
              "name" => $term->name
            ];  
        }
    }else{
      foreach ($terms as $term) {
        $options[$term->tid] = $term->name;
      }
    }
    return new JsonResponse($options);
  }

}
