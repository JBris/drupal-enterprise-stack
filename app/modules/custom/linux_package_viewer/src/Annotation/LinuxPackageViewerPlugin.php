<?php

namespace Drupal\linux_package_viewer\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a LinuxPackageViewerPlugin type annotation object.
 *
 * LinuxPackageViewerPlugin classes define Linux package viewers for the linux_package_viewer module. 
 *
 * @Annotation
 */
class LinuxPackageViewerPlugin extends Plugin {

  /**
   * The unique plugin ID.
   * 
   * @var string
   */
  public $id;

  /**
   * The Linux distribution.
   * 
   * @var string
   */
  public $distribution;
  
}
