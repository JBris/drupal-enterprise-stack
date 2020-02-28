<?php

namespace Drupal\linux_package_viewer\Plugin\LinuxPackageViewer;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\linux_package_viewer\Plugin\LinuxPackageViewerPluginBase;

/**
 * @LinuxPackageViewerPlugin(
 *   id = "ubuntu_viewer",
 *   distribution = "Ubuntu"
 * )
 */
class UbuntuViewer extends LinuxPackageViewerPluginBase implements ContainerFactoryPluginInterface {
    const SEARCH_URL = 'https://api.launchpad.net/1.0/ubuntu/+archive/primary';

    /**
    * {@inheritdoc}
    */
    public function executeRaw() {
        $package = $this->getPackage();
        if ($package === "") { return []; }

        $url = $this->getSearchUrl();
        $results = $this->httpClient->get($url, [
            "query" => [           
                "ws.op" => "getPublishedSources",
                "exact_match" => "false",
                "source_name" => $package
            ]
        ]);
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

    /**
    * {@inheritdoc}
    */
    public function getSearchUrl() {
        return static::SEARCH_URL;
    }
}