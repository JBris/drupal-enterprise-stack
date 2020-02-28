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

        try {
            $results = $this->httpClient->get("${url}/${searchString}");
        } catch (\Exception $e) {
            return [];
        }

        $body = $results->getBody();
        $decodedBody = json_decode($body);
        return $decodedBody;
    }

    /**
    * {@inheritdoc}
    */
    public function execute() {
        $packages = $this->executeRaw();
        return $this->extractPackageNames($packages);
    }

    /**
     * Extract package names from an object of data.
     * 
     * @return array
     *  The list of package names.
     */
    protected function extractPackageNames($packages){
        $results = [];
        if(!isset($packages->rows)) { return $results; }
        $rows = $packages->rows;

        foreach($rows as $row) {
            $results[] = $row->name;
        }

        return $results;
    }
}