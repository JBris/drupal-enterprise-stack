<?php

namespace Drupal\linux_package_viewer\Form;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The Linux Package search form.
 */
class SearchForm extends FormBase { 
    
    /**
     * The Linux Package Viewer Plugin Manager.
     * 
     * @var PluginManagerInterface
     */
    protected $pluginManager;

    /**
    * Constructs a new FileTransferAuthorizeForm object.
    *
    * @param string $root
    *   The app root.
    */
    public function __construct(PluginManagerInterface $plugin_manager) {
        $this->pluginManager = $plugin_manager;
    }

    /**
    * {@inheritdoc}
    */
    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('plugin.manager.linux_package_viewer')
        );
    }

    public function getFormId() {
        return 'linux_package_viewer_search_form';
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $form_state->setRebuild(false);
    }

    public function buildForm(array $form, FormStateInterface $form_state) {

        $definitions = $this->pluginManager->getDefinitions();
        $form['distribution'] = [
            '#type' => 'select',
            '#title' => $this->t('Distribution'),
        ];

        foreach($definitions as $id => $definition) {
            $form['distribution']['#options'][$id] = $this->t($definition['distribution']);  
        }

        $form['package'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Package'),
            '#size' => 60,
            '#maxlength' => 128,
        ];
  
        $form['save'] = [
            '#type' => 'submit',
            '#value' => $this->t('Search'),
            '#suffix' => '<br/>',
            '#ajax' => [
                'callback' => '::searchPackages',
                'wrapper' => 'linux-package-viewer-search-results-wrapper', 
                'event' => 'click',
                'progress' => [
                    'type' => 'throbber',
                    'message' => NULL,
                ]
            ]
        ];

        $form['results'] = [
            '#type' => 'container',
            '#prefix' => '<div id="linux-package-viewer-search-results-wrapper">',
            '#suffix' => '</div>',
        ];

        return $form;
    }

    public function searchPackages($form, FormStateInterface $formState) {
        $package = trim($formState->getValue('package'));
        if ($package === '') { return $form['results']; }

        $distribution = $formState->getValue('distribution');
        $instance = $this->pluginManager->createInstance($distribution);
        $instance->setPackage($package);
        $results = $instance->search();

        $ele = [
            '#type' => 'table',
            '#header' => [
                $this->t('Package'),
                $this->t('View'),
            ],
        ];

        foreach($results as $i => $result){
            $ele[$i]['#attributes'] = [
                'class' => ['linux-package-viewer-search-result']
            ];

            $ele[$i]['package'] = [
                '#plain_text' => $this->t($result),
            ];
        }
        
        $ele['#prefix'] = '<br/> <div id="linux-package-viewer-search-results-wrapper">';
        $ele['#suffix'] = '</div>';
        return $ele;
    }

}
