<?php

namespace Drupal\web_scraper\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configures web scraper settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'web_scraper_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['web_scraper.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('web_scraper.settings');

    $form['web_scraper_number_of_items_to_list'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of items to list'),
      '#min' => 1,
      '#size' => 2,
      '#step' => 1,
      '#default_value' => $config->get('number_of_items_to_list'),
      '#description' => $this->t('The number of items the controller will list per page on the custom content entity listing page.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('web_scraper.settings')
      ->set('number_of_items_to_list', $form_state->getValue('web_scraper_number_of_items_to_list'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
