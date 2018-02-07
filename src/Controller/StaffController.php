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
     
    $results = people_finder_results('',$dept,'');
    
    $build = array();
    $build['#markup'] = $results;
    if ((strpos(strtolower($dept), 'division') !== false) || (strpos(strtolower($dept), 'health sciences') !== false)) {
      $build['#title'] = ucwords($dept) . ' Faculty & Staff';
    } else {
      $build['#title'] = ucwords($dept) . ' Staff';
    }    
    return $build;
    
  }
  
  /**
   * Display staff for a specific title.
   * Input: dept
   * URL: /directory/{dept}/{title}
   *
   * @return array
  */
  public function byTitle(String $dept, String $job_title) {
     
    $results = people_finder_results('',$dept,$job_title);
    
    $build = array();
    $build['#markup'] = $results;
    if ((strpos(strtolower($dept), 'division') !== false) || (strpos(strtolower($dept), 'health sciences') !== false)) {
      if (strpos(strtolower($job_title), 'faculty') !== false) {
        $build['#title'] = ucwords($job_title);
      } else {
        $build['#title'] = ucwords($job_title) . ' Faculty';
      }
    } else {
      $build['#title'] = ucwords($job_title) . ' Staff';
    }
    return $build;
    
  }
  
 }