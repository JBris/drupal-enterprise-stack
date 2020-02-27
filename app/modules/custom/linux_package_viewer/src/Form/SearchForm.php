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

    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['title'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Search'),
            '#size' => 60,
            '#maxlength' => 128,
            '#required' => TRUE,
        ];
  
        $form['save'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Save'),
        );

        return $form;
    }

}
