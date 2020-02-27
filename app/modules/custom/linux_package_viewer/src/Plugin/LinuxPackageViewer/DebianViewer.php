<?php

namespace Drupal\linux_package_viewer\Plugin\LinuxPackageViewer;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\linux_package_viewer\Plugin\LinuxPackageViewerPluginBase;

/**
 * @LinuxPackageViewerPlugin(
 *   id = "debian_viewer",
 *   distribution = "Debian"
 * )
 */
class DebianViewer extends LinuxPackageViewerPluginBase implements ContainerFactoryPluginInterface {
    const SEARCH_URL = 'https://sources.debian.org/api/search';

    /**
    * {@inheritdoc}
    */
    public function execute() {
        $package = $this->getPackage();
        if ($package === "") { return []; }

        $url = $this->getSearchUrl();
        $results = $this->httpClient->get("${url}/${package}");
        $body = $results->getBody();
        $decodedBody = json_decode($body);
        return $decodedBody;
    }

    public function getSearchUrl() {
        return static::SEARCH_URL;
    }
}