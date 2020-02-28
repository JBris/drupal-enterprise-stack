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
    
    const SEARCH_URL = 'https://sources.debian.org/api';

    /**
    * {@inheritdoc}
    */
    public function searchRaw() {
        $package = $this->getPackage();
        if ($package === "") { return []; }
        $url = $this->getSearchUrl();

        try {
            $results = $this->httpClient->get("${url}/search/${package}");
        } catch (\Exception $e) {
            return ["error" => "1", "message" => $e->getMessage()];
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
    * {@inheritdoc}
    */
    public function viewRaw() {
        $package = $this->getPackage();
        if ($package === "") { return []; }
        $url = $this->getSearchUrl();

        try {
            $results = $this->httpClient->get("${url}/src/${package}");
        } catch (\Exception $e) {
            return ["error" => "1", "message" => $e->getMessage()];
        }

        $body = $results->getBody();
        $decodedBody = json_decode($body);
        return $decodedBody;
    }

    /**
    * {@inheritdoc}
    */
    public function view() {
        $info = $this->viewRaw();
        return $this->flattenPackageInfo($info);
    }

    /**
     * Extract package names from an object of data.
     * 
     * @return array
     *  The list of package names.
     */
    protected function extractPackageNames($packages){
        if (isset($packages->error)) { return $packages; }
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

    /**
     * Flatten and extract relevant data from a package set.
     * 
     * @return array
     *  The collection of package information.
     */
    protected function flattenPackageInfo($info) {
        if (isset($info->error)) { return $info; }
        $results = [];
        if(!isset($info->versions)) { return $results; }
        $versions = $info->versions;

        foreach($versions as $version) {
            $result = $version;
            $result->name = $info->package;
            $result->displayName = $result->name . "-" . $result->version;
            $results[] = $result;
        }

        return $results;
    }
}