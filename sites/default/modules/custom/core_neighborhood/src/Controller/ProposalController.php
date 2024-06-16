<?php

namespace Drupal\core_neighborhood\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\core_neighborhood\Form\ProposalForm;

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

}
