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
    use GetSearchUrlTrait;

    const SEARCH_URL = 'https://apps.fedoraproject.org/packages/fcomm_connector/xapian/query/search_packages';

    /**
    * {@inheritdoc}
    */
    public function executeRaw() {
        $package = $this->getPackage();
        if ($package === "") { return []; }

        $url = $this->getSearchUrl();
        $search = [
            "filters" => [
                "search" => $package
            ], 
            "rows_per_page" => 10,
            "start_row" => 0,
        ];
        $searchString = json_encode($search);
        $results = $this->httpClient->get("${url}/${searchString}");
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