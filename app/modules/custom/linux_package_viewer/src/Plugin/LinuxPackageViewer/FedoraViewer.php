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

    const SEARCH_URL = 'https://apps.fedoraproject.org/packages/fcomm_connector';
    const ROWS_PER_PAGE = 30; 

    /**
    * {@inheritdoc}
    */
    public function searchRaw() {
        $package = $this->getPackage();
        if ($package === "") { return []; }

        $url = $this->getSearchUrl();
        $search = [
            "filters" => [
                "search" => $package
            ], 
            "rows_per_page" => $this->getRowsPerPage(),
            "start_row" => 0,
        ];
        $searchString = json_encode($search);

        try {
            $results = $this->httpClient->get("${url}/xapian/query/search_packages/${searchString}");
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
        $search = [
            "filters" => [
                "package" => $package
            ], 
            "rows_per_page" => $this->getRowsPerPage(),
            "start_row" => 0,
        ];
        $searchString = json_encode($search);

        try {
            $results = $this->httpClient->get("${url}/koji/query/query_builds/${searchString}");
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
    * {@inheritdoc}
    */
    public function getRowsPerPage() {
        return static::ROWS_PER_PAGE;
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
        if(!isset($packages->rows)) { return $results; }
        $rows = $packages->rows;

        foreach($rows as $row) {
            $results[] = $row->name;
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
        if(!isset($info->rows)) { return $results; }
        $rows = $info->rows;

        foreach($rows as $row) {
            $result = (object) [];
            $result->name = $row->package_name;
            $result->source = $row->source;
            $result->version = $row->version;
            $result->displayName = $row->nvr;
            $result->release = $row->release;
            $result->owner = $row->owner_name;
            $result->creationTime = $row->creation_ts;
            $results[] = $result;
        }

        return $results;
    }
    
}