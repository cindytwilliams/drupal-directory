<?php

namespace Drupal\vscc_ldap\Plugin\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Provides an 'Staff' block.
 *
 * @Block(
 *   id = "staff_block",
 *   admin_label = @Translation("Staff block"),
 *   category = @Translation("Custom Staff block")
 * )
 */
class StaffBlock extends BlockBase {
  
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    // get the program name (from page title)
    $request = \Drupal::request();
    $route_match = \Drupal::routeMatch();
    if ($route_match->getParameter('node')) {
      $job_title = \Drupal::service('title_resolver')->getTitle($request, $route_match->getRouteObject());
    }
    
    $output = people_finder_results('','',$job_title);
    
    return [
      '#type' => 'markup',
      '#markup' => $output,
    ];

  }

}