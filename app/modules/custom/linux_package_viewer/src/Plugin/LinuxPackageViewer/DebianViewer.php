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

        try {
            $results = $this->httpClient->get("${url}/${package}");
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
        if(!isset($packages->results)) { return $results; }
        $packageResults = $packages->results;

        if(isset($packageResults->exact->name)) {
            $results[] = $packageResults->exact->name;
        }

        if (!isset($packageResults->other)) { return $results; }

        foreach($packageResults->other as $package) {
            $results[] = $package->name;
        }

        return $results;
    }

}