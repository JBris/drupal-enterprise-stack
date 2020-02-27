<?php

namespace Drupal\linux_package_viewer\Plugin;

use Drupal\Core\Cache\RefinableCacheableDependencyInterface;
use Drupal\Core\Cache\RefinableCacheableDependencyTrait;
use Drupal\Core\Plugin\PluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a base class for plugins wishing to support linux_package_viewer.
 */
abstract class LinuxPackageViewerPluginBase extends PluginBase implements ContainerFactoryPluginInterface, LinuxPackageViewerInterface, RefinableCacheableDependencyInterface {

  use RefinableCacheableDependencyTrait;

  /**
   * The package to use in a search.
   *
   * @var string
   */
  protected $package;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration, 
      $plugin_id, 
      $plugin_definition
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setPackage(string $package) {
    $this->package = $package;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPackage() {
    return $this->package;
  }

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return NULL;
  }

}
