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

    $options = $this->getTaxonomyTermOptions('tax_situation'); 

    $form['tax_situation_reference'] = [
      '#type' => 'select',
      '#title' => $this->t('Situación impositiva'),
      '#options' => $options,
      '#required' => TRUE,
    ];
    
    $form['field_valid_since'] = [
        '#type' => 'datetime',
        '#title' => $this->t('Vigencia desde'),
        '#required' => TRUE,
        '#default_value' => $currentDatetime,
    ];
    
    $form['field_validity_until'] = [
        '#type' => 'datetime',
        '#title' => $this->t('Vigencia hasta'),
        '#required' => TRUE,
    ];

    $form['payment_type_reference'] = [
      '#type' => 'select',
      '#title' => $this->t('Tipo de pago'),
      '#options' => [
        '' => 'Seleccione una opción',
        'contado' => 'CONTADO',
        'credito' => 'CRÉDITO'
      ],
      '#required' => TRUE,
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
    if (strlen($form_state->getValue('name')) < 5) {
      $form_state->setErrorByName('name', $this->t('Name must be at least 5 characters long.'));
    }

    if (!\Drupal::service('email.validator')->isValid($form_state->getValue('email'))) {
      $form_state->setErrorByName('email', $this->t('The email address is not valid.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
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
  protected function getTaxonomyTermOptions($vocabulary) {
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vocabulary);
    $options = [];
    foreach ($terms as $term) {
      $options[$term->tid] = $term->name;
    }
    return $options;
  }

}
