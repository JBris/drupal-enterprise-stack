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
        $id = "${distribution}_viewer";
        $definitions = $this->pluginManager->getDefinitions();

        if (!isset($definitions[$id])) {
            $form['error'] = [
                '#prefix' => '<br/>',
                '#plain_text' => $this->t("Error: '${distribution}' distribution is not supported."),
                '#suffix' => '<br/>',
            ];
            return $form;
        }

        $instance = $this->pluginManager->createInstance($id);
        $package = $this->routeMatch->getParameter('package');
        $instance->setPackage($package);

        // $definitions = $this->pluginManager->getDefinitions();
        // $form['distribution'] = [
        //     '#type' => 'select',
        //     '#title' => $this->t('Distribution'),
        // ];

        // foreach($definitions as $id => $definition) {
        //     $form['distribution']['#options'][$id] = $this->t($definition['distribution']);  
        // }

        // $form['package'] = [
        //     '#type' => 'textfield',
        //     '#title' => $this->t('Package'),
        //     '#size' => 60,
        //     '#maxlength' => 128,
        // ];
  
        // $form['save'] = [
        //     '#type' => 'submit',
        //     '#value' => $this->t('Search'),
        //     '#suffix' => '<br/>',
        //     '#ajax' => [
        //         'callback' => '::searchPackages',
        //         'wrapper' => 'linux-package-viewer-search-results-wrapper', 
        //         'event' => 'click',
        //         'progress' => [
        //             'type' => 'throbber',
        //             'message' => NULL,
        //         ]
        //     ]
        // ];

        // $form['results'] = [
        //     '#type' => 'container',
        //     '#prefix' => '<div id="linux-package-viewer-search-results-wrapper">',
        //     '#suffix' => '</div>',
        // ];

        return $form;
    }
}
