<?php

namespace Drupal\linux_package_viewer\Plugin\LinuxPackageViewer;

/**
 * Provides a trait to retrieve a search URL.
 */
trait GetSearchUrlTrait { 
    
    /**
    * {@inheritdoc}
    */
    public function getSearchUrl() {
        return static::SEARCH_URL;
    }
}