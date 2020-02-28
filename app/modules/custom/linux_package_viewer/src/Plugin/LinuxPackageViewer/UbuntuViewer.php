<?php

namespace Drupal\linux_package_viewer\Plugin\LinuxPackageViewer;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\linux_package_viewer\Plugin\LinuxPackageViewerPluginBase;

/**
 * @LinuxPackageViewerPlugin(
 *   id = "ubuntu",
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
                ]
            ]);
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
            $results = $this->httpClient->get($url, [
                "query" => [           
                    "ws.op" => "getPublishedSources",
                    "exact_match" => "true",
                    "source_name" => $package,
                    "ws.size" => "200",
                    "ordered" => "false",
                ]
            ]);
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
    public function render() {
        $results = $this->view();
        if (isset($results->error)) {
            $msg = $results->message;
            $ele = [
                '#prefix' => '<br/>',
                '#plain_text' => $this->t("Error: ${msg}"),
                '#suffix' => '<br/>',
            ];
            return $ele;
        }

        foreach($entries as $entry) {
            $result = (object) [];
            $result->name = $entry->source_package_name;
            $result->displayName = $entry->display_name;
            $result->version = $entry->source_package_version;
            $result->status = $entry->status;
            $result->publishDate = $entry->date_published;
            $results[] = $result;
        }

        $ele = [
            '#type' => 'table',
            '#header' => [
                $this->t('Name'),
                $this->t('Display'),
                $this->t('Version'),
                $this->t('Status'),
                $this->t('Date'),
            ],
        ];

        foreach($results as $i => $result) {
            $ele[$i]['#attributes'] = [
                'class' => ['linux-package-viewer-package-view']
            ];

            $ele[$i]['name'] = [
                '#plain_text' => $result->name,
            ];

            $ele[$i]['display'] = [
                '#plain_text' => $result->displayName,
            ];

            $ele[$i]['version'] = [
                '#plain_text' => $result->version,
            ];
            
            $ele[$i]['status'] = [
                '#plain_text' => $result->status,
            ];
    
            $ele[$i]['date'] = [
                '#plain_text' => $result->publishDate,
            ];
        }
        
        $ele['#prefix'] = '<div id="linux-package-viewer-package-view-wrapper">';
        $ele['#suffix'] = '</div>';
        return $ele;
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
        if(!isset($packages->entries)) { return $results; }
        $entries = $packages->entries;

        foreach($entries as $entry) {
            $key = $entry->source_package_name;
            $results[$key] = true;
        }

        return array_keys($results);
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
        if(!isset($info->entries)) { return $results; }
        $entries = $info->entries;

        foreach($entries as $entry) {
            $result = (object) [];
            $result->name = $entry->source_package_name;
            $result->displayName = $entry->display_name;
            $result->version = $entry->source_package_version;
            $result->status = $entry->status;
            $result->publishDate = $entry->date_published;
            $results[] = $result;
        }

        return $results;
    }
}