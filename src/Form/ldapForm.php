<?php

namespace Drupal\vscc_ldap\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements an ldap form.
 */
class ldapForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ldap_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // create last name field
    $form['last_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Last Name'),
    );
    $form['last_name']['#weight'] = -3;  // position of field
    
    // query departments from taxonomy
    /*$depts = array();
    $depts[] = '';
    $vid = 'departments';
    $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
    foreach ($terms as $term) {
      $depts[] = $term->name;
    }*/
    
    // query departments from database
    $connection = \Drupal\Core\Database\Database::getConnection('default','webdb');
    $query = $connection->select('people', 't')->fields('t', array('department'));
    $query->orderBy('department', 'ASC');
    $result = $query->execute();
    
    // store departments in an array
    $depts = array();
    $depts[] = '';
    while($record = $result->fetchAssoc()) {
      if ($record['department'] == 'Office of the President')
        $depts[] = $record['department'];
      else
        $depts[] = str_replace('Division of ', '', str_replace('Asst ', '', str_replace('Vice President for ', '', str_replace('Office of ', '', str_replace('Office of the ', '', $record['department'])))));
    }
    sort($depts); // sort the array
    
    // set connection back to main Drupal database
    \Drupal\Core\Database\Database::setActiveConnection();

    // create departments dropdown field from array values
    $form['dept'] = array(
      '#type' => 'select',
      '#title' => $this->t('Department'),
      '#options' => array_combine($depts, $depts)
    );
    $form['dept']['#weight'] = -2;  // position of field
    
    // create title field
    $form['job_title'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
    );
    $form['job_title']['#weight'] = -1;  // position of field
    
    // submit button
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Search'),
      '#button_type' => 'primary',
    );
    
    // position submit button below search fields and above results
    $form['submit'] = $form['actions']['submit'];
    $form['submit']['#weight'] = 0;
    unset($form['actions']['submit']);
    
    // display results
    $values = $form_state->getValues();
    if (!empty($values)) {
      $last_name = $values['last_name'];
      $dept = $values['dept'];
      $job_title = $values['job_title'];
      $results = people_finder_results($last_name,$dept,$job_title);     // call function that searches LDAP
      $form['result'] = ['#markup' => $results];
    }

    return $form;
    
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ( (strlen($form_state->getValue('last_name')) == 0) && (strlen($form_state->getValue('dept')) == 0) && (strlen($form_state->getValue('job_title')) == 0) ) {
      $form_state->setErrorByName('last_name', $this->t('You must enter a Last Name, Department, or Title.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    //drupal_set_message($this->t('Last Name: @last_name', array('@last_name' => $form_state->getValue('last_name'))));
    $form_state->setRebuild();
    
  }

}   // end class