<?php

namespace Drupal\linux_package_viewer\Plugin\LinuxPackageViewer;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\linux_package_viewer\Plugin\LinuxPackageViewerPluginBase;

/**
 * @LinuxPackageViewerPlugin(
 *   id = "fedora_viewer",
 *   distribution = "Fedora"
 * )
 */
class FedoraViewer extends LinuxPackageViewerPluginBase implements ContainerFactoryPluginInterface {
    const SEARCH_URL = 'https://apps.fedoraproject.org/packages/fcomm_connector/xapian/query/search_packages';

    /**
    * {@inheritdoc}
    */
    public function execute() {
        $package = $this->getPackage();
        if ($package === "") { return []; }

        $url = $this->getSearchUrl();
        $search = [
            "filters" => [
                "search" => $package
            ], 
            "rows_per_page" => 10,
            "start_row" => 10,
        ];
        $searchString = json_encode($search);
        $results = $this->httpClient->get("${url}/${searchString}");
        $body = $results->getBody();
        $decodedBody = json_decode($body);
        return $decodedBody;
    }

    public function getSearchUrl() {
        return static::SEARCH_URL;
    }
}