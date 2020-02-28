<?php

namespace Drupal\linux_package_viewer\Form;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The Linux Package search form.
 */
class ViewForm extends FormBase { 
    
    /**
     * The Linux Package Viewer Plugin Manager.
     * 
     * @var PluginManagerInterface
     */
    protected $pluginManager;

    /**
    * The route match.
    *
    * @var RouteMatchInterface
    */
    protected $routeMatch;

    /**
    * Constructs a new FileTransferAuthorizeForm object.
    *
    * @param PluginManagerInterface $plugin_manager
    *   The plugin manager.
    *
    * @param RouteMatchInterface $route_match
    *   The route match.
    */
    public function __construct(PluginManagerInterface $plugin_manager, RouteMatchInterface $route_match) {
        $this->pluginManager = $plugin_manager;
        $this->routeMatch = $route_match;
    }

    /**
    * {@inheritdoc}
    */
    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('plugin.manager.linux_package_viewer'),
            $container->get('current_route_match')
        );
    }

    public function getFormId() {
        return 'linux_package_viewer_view_form';
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $form_state->setRebuild(false);
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $distribution = $this->routeMatch->getParameter('distribution');
        $definitions = $this->pluginManager->getDefinitions();

        if (!isset($definitions[$distribution])) {
            $form['error'] = [
                '#prefix' => '<br/>',
                '#plain_text' => $this->t("Error: '${distribution}' distribution is not supported."),
                '#suffix' => '<br/>',
            ];
            return $form;
        }

        $instance = $this->pluginManager->createInstance($distribution);
        $package = $this->routeMatch->getParameter('package');
        $instance->setPackage($package);
        $form['view_package'] = [
            '#type' => 'container',
            '#prefix' => '<div id="linux-package-viewer-view-package">',
            '#suffix' => '</div>',
        ];
        $form['view_package']['package_info'] = $instance->render(); 
        return $form;
    }
}
