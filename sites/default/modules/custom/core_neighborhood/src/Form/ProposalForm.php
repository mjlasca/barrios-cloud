<?php

namespace Drupal\core_neighborhood\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Datetime\DrupalDateTime;
/**
 * Implements a custom form.
 */
class ProposalForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $currentDatetime = new DrupalDateTime('now', new \DateTimeZone('America/Argentina/Buenos_Aires'));
    $form['custommer_reference'] = [
        '#type' => 'entity_autocomplete',
        '#title' => $this->t('Tomador'),
        '#target_type' => 'node',
        '#selection_handler' => 'default',
        '#selection_settings' => [
          'target_bundles' => ['custommer'], // Reemplaza con tu tipo de contenido
        ],
        '#required' => TRUE,
    ];
    /*$form['tax_situation_reference'] = [
        '#type' => 'entity_autocomplete',
        '#title' => $this->t('Situación impositiva'),
        '#target_type' => 'taxonomy_term',
        '#selection_handler' => 'default',
        '#selection_settings' => [
          'target_bundles' => ['tax_situation'], // Reemplaza con tu tipo de contenido
        ],
        '#required' => TRUE,
    ];*/

    $options_tax_situation = $this->getTaxonomyTermOptions('tax_situation'); 

    $form['tax_situation_reference'] = [
      '#type' => 'select',
      '#title' => $this->t('Situación impositiva'),
      '#options' => $options_tax_situation,
    ];
    
    $form['field_valid_since'] = [
        '#type' => 'datetime',
        '#title' => $this->t('Vigencia desde'),
        '#default_value' => $currentDatetime,
    ];
    
    $form['field_validity_until'] = [
        '#type' => 'datetime',
        '#title' => $this->t('Vigencia hasta'),
    ];

    $form['payment_type_reference'] = [
      '#type' => 'select',
      '#title' => $this->t('Tipo de pago'),
      '#options' => [
        '' => 'Seleccione una opción',
        'contado' => 'CONTADO',
        'credito' => 'CRÉDITO'
      ],
    ];

    $form['document_number'] = [
      '#type' => 'number',
      '#empty_option' => $this->t('No. documento'),
      '#name' => 'document_number[]',
      '#attributes' => [
        'placeholder' => 'No. documento'
      ]
    ];

    $options_document_type = $this->getTaxonomyTermOptions('document_type'); 

    $form['document_type'] = [
      '#type' => 'select',
      '#options' => $options_document_type,
      '#empty_option' => $this->t('- Tipo doc. -'),
      '#name' => 'document_type[]',
    ];

    $options_activity_clasification = $this->getTaxonomyTermOptions('activity_clasification', 0); 

    $form['activity_clasification'] = [
      '#type' => 'select',
      '#options' => $options_activity_clasification,
      '#empty_option' => $this->t('- Actividad -'),
      '#name' => 'activity_clasification[]',
    ];
    
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    /*if (strlen($form_state->getValue('name')) < 5) {
      $form_state->setErrorByName('name', $this->t('Name must be at least 5 characters long.'));
    }*/
    dd($form_state->getValues());
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    dump($form_state->getValues());
    exit;
    \Drupal::messenger()->addMessage($this->t('The form has been submitted.'));
  }

  /**
   * Get taxonomy term options for a specific vocabulary.
   *
   * @param string $vocabulary
   *   The vocabulary ID.
   *
   * @return array
   *   An associative array of taxonomy term options.
   */
  protected function getTaxonomyTermOptions($vocabulary, $parent = -1) {
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vocabulary);
    $options = [];
    if($parent != -1){
        foreach ($terms as $term) {
          if(isset($term->parents[0]) && $term->parents[0] == $parent)
            $options[$term->tid] = $term->name;  
        }
    }else{
      foreach ($terms as $term) {
        $options[$term->tid] = $term->name;
      }
    }
    return $options;
  }

}
