<?php

namespace Drupal\vscc_ldap\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'LDAP' Block.
 *
 * @Block(
 *   id = "ldap_block",
 *   admin_label = @Translation("LDAP block"),
 *   category = @Translation("LDAP block"),
 * )
 */
class LDAPBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    
    // get job title from URL
    $current_path = \Drupal::service('path.current')->getPath();
    $path_alias = \Drupal::service('path.alias_manager')->getAliasByPath($current_path, $langcode = NULL);
    $link_array = explode('/',$path_alias);
    $job_title = end($link_array);
    
    if ($job_title != '') {
    
      // call function that searches LDAP
      $results = people_finder_results('','',$job_title);
      
      // send results to Twig template
      return array(
        '#theme' => 'vscc_ldap_page',
        '#type' => 'markup',
        '#results' => $results,
      );  
    }
    
    else {
      return array(
        '#markup' => $this->t(''),
      ); 
      
    }

  }

}