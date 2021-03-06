<?php

namespace Drupal\linux_package_viewer\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Component\Serialization\Json;

class LinuxPackageViewerApiController extends ControllerBase {

    /**
     * The Linux Package Viewer Plugin Manager.
     * 
     * @var PluginManagerInterface
     */
    protected $pluginManager;

    /**
    * LinuxPackageViewerApiController constructor.
    *
    * @param PluginManagerInterface $plugin_manager
    *   The Linux Package Viewer Plugin Manager service.
    */
    public function __construct(PluginManagerInterface $plugin_manager) {
        $this->pluginManager = $plugin_manager;
    }

    /**
    * {@inheritdoc}
    */
    public static function create(ContainerInterface $container) {
        return new static (
            $container->get('plugin.manager.linux_package_viewer')        
        );
    }

    public function search(Request $request, $distribution, $package) {
        $definitions = $this->pluginManager->getDefinitions();

        if (!isset($definitions[$distribution])) {
            return new JsonResponse(["error" => 1, "message" => "Unsupported distribution: ${distribution}"], 400);
        }
        $instance = $this->pluginManager->createInstance($distribution);
        $instance->setPackage($package);
        $results = $instance->search();
        $status = 200;
        if (isset($results->error)) { $status = 400; }
        return new JsonResponse($results, $status); 
    }

    public function view(Request $request, $distribution, $package) {
        $definitions = $this->pluginManager->getDefinitions();

        if (!isset($definitions[$distribution])) {
            return new JsonResponse(["error" => 1, "message" => "Unsupported distribution: ${distribution}"], 400);
        }
        $instance = $this->pluginManager->createInstance($distribution);
        $instance->setPackage($package);
        $results = $instance->view();
        $status = 200;
        if (isset($results->error)) { $status = 400; }
        return new JsonResponse($results, $status); 
    }
 }
