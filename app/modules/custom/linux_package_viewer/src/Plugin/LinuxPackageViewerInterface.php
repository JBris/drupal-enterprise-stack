<?php

namespace Drupal\linux_package_viewer\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a common interface for all LinuxPackageViewerPlugin objects.
 */
interface LinuxPackageViewerInterface extends PluginInspectionInterface {

  /**
   * Sets the package to be used by execute().
   *
   * @param string $package
   *   The package to use in a search.
   * 
   * @return $this
   *   A LinuxPackageViewerPlugin object for chaining.
   */
  public function setPackage(string $package);

    /**
   * Returns the currently set package of the plugin instance.
   *
   * @return string
   *   The package.
   */
  public function getPackage();

  /**
   * Executes the search.
   *
   * @return array
   *   A structured list of search results.
   */
  public function search();

  
  /**
   * Executes the search and return the raw results.
   *
   * @return array
   *   A structured list of search results.
   */
  public function searchRaw();

    /**
   * Executes the view.
   *
   * @return array
   *   A structured list of view results.
   */
  public function view();

  
  /**
   * Executes the view and return the raw results.
   *
   * @return array
   *   A structured list of view results.
   */
  public function viewRaw();

  /**
   * Render the package information.
   * 
   * @return array
   *  A render array of package information.
   */
  public function render();

  /**
   * Return the base package search URL
   * 
   * @return string
   *  The base package search URL.
   */
  public function getSearchUrl();

}
