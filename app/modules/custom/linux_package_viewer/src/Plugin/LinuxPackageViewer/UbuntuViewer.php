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
    use GetSearchUrlTrait;

    const SEARCH_URL = 'https://api.launchpad.net/1.0/ubuntu/+archive/primary';

    /**
    * {@inheritdoc}
    */
    public function searchRaw() {
        $package = $this->getPackage();
        if ($package === "") { return []; }
        $url = $this->getSearchUrl();

        try {
            $results = $this->httpClient->get($url, [
                "query" => [           
                    "ws.op" => "getPublishedSources",
                    "exact_match" => "false",
                    "source_name" => $package,
                    "ws.size" => "200",
                    "ordered" => "false",
                    "memo" => "200"
                ]
            ]);
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
    public function search() {
        $packages = $this->searchRaw();
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
        if(!isset($packages->entries)) { return $results; }
        $entries = $packages->entries;

        foreach($entries as $entry) {
            $key = $entry->source_package_name;
            $results[$key] = true;
        }

        return array_keys($results);
    }
}