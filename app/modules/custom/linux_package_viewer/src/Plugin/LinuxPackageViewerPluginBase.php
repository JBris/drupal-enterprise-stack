<?php

namespace Drupal\linux_package_viewer\Plugin;

use Drupal\Core\Cache\RefinableCacheableDependencyInterface;
use Drupal\Core\Cache\RefinableCacheableDependencyTrait;
use Drupal\Core\Plugin\PluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a base class for plugins wishing to support linux_package_viewer.
 */
abstract class LinuxPackageViewerPluginBase extends PluginBase implements ContainerFactoryPluginInterface, LinuxPackageViewerInterface, RefinableCacheableDependencyInterface {

  use RefinableCacheableDependencyTrait;
  use StringTranslationTrait;

  /**
   * The package to use in a search.
   *
   * @var string
   */
  protected $package;

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * Constructs a new OEmbed instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The HTTP client.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ClientInterface $http_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration, 
      $plugin_id, 
      $plugin_definition,
      $container->get('http_client')
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
