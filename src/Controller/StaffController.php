<?php

namespace Drupal\vscc_ldap\Controller;
use Drupal\Core\Controller\ControllerBase;

class StaffController extends ControllerBase {
  
  /**
   * Display staff for a specific department.
   * Input: dept
   * URL: /directory/{dept}
   *
   * @return array
  */
  public function byDept(String $dept) {
    
    // for divisions, display "faculty" in the title
    if (
      (strpos(strtolower($dept), 'business') !== false) 
      || (strpos(strtolower($dept), 'health sciences') !== false)
      || (strpos(strtolower($dept), 'humanities') !== false)
      || (strpos(strtolower($dept), 'mathematics') !== false)
      || (strpos(strtolower($dept), 'social science') !== false)
      ) {
      $pageTitle = ucwords($dept) . ' Faculty & Staff';
    } 
    else {
      $pageTitle = ucwords($dept) . ' Staff';
    }    
    
    // call function that searches LDAP
    $results = people_finder_results('',$dept,'');
    
    // send results to Twig template
    return array(
      '#theme' => 'vscc_ldap_page',
      '#type' => 'markup',
      '#title' => $pageTitle,
      '#results' => $results,
    );    
  }
  
  /**
   * Display staff for a specific title.
   * Input: dept
   * URL: /directory/{dept}/{title}
   *
   * @return array
  */
  public function byTitle(String $dept, String $job_title) {
     
    if ((strpos(strtolower($dept), 'division') !== false) || (strpos(strtolower($dept), 'health sciences') !== false)) {
      if (strpos(strtolower($job_title), 'faculty') !== false) {
        $pageTitle = ucwords($job_title);
      } 
      else {
        $pageTitle = ucwords($job_title) . ' Faculty';
      }
    } 
    else {
      $pageTitle = ucwords($job_title) . ' Staff';
    }
    
    // call function that searches LDAP
    $results = people_finder_results('',$dept,$job_title);
    
    // send results to Twig template
    return array(
      '#theme' => 'vscc_ldap_page',
      '#type' => 'markup',
      '#title' => $pageTitle,
      '#results' => $results,
    );  
        
  }
  
 }