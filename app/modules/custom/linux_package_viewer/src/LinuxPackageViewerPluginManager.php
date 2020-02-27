<?php

namespace Drupal\linux_package_viewer;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\linux_package_viewer\Annotation\LinuxPackageViewerPlugin;
use Drupal\linux_package_viewer\Plugin\LinuxPackageViewerInterface;
 
class LinuxPackageViewerPluginManager extends DefaultPluginManager {

  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/LinuxPackageViewer', $namespaces, $module_handler, LinuxPackageViewerInterface::class, LinuxPackageViewerPlugin::class);
    $this->setCacheBackend($cache_backend, 'linux_package_viewer_plugins');
    $this->alterInfo('linux_package_viewer_plugin');
  }

}
