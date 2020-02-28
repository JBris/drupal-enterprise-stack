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
    use GetSearchUrlTrait;
    
    const SEARCH_URL = 'https://sources.debian.org/api/search';

    /**
    * {@inheritdoc}
    */
    public function executeRaw() {
        $package = $this->getPackage();
        if ($package === "") { return []; }

        $url = $this->getSearchUrl();
        $results = $this->httpClient->get("${url}/${package}");
        $body = $results->getBody();
        $decodedBody = json_decode($body);
        return $decodedBody;
    }

    /**
    * {@inheritdoc}
    */
    public function execute() {
        return $this->executeRaw();
    }

}