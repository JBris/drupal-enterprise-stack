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

    public function search(Request $request) {
        return new Response(Json::encode(["hello" => "world"])); 
    }
 }
