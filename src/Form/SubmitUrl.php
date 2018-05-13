<?php

namespace Drupal\web_scraper\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\web_scraper\WebScraperInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SubmitUrl extends FormBase {

  /**
   * The HTTP client to fetch the URL with.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * The Web Scraper service
   *
   * @var \Drupal\web_scraper\WebScraperInterface
   */
  protected $webScraper;

  /**
   * Constructs a SubmitUrl object.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The Guzzle HTTP client.
   * @param \Drupal\web_scraper\WebScraperInterface $web_scraper
   *   The web scraper service.
   */
  public function __construct(ClientInterface $http_client, WebScraperInterface $web_scraper) {
    $this->httpClient = $http_client;
    $this->webScraper = $web_scraper;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client'),
      $container->get('web_scraper.web_scraper')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'web_scraper_submit_url';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['source'] = [
      '#type' => 'url',
      '#title' => $this->t('Source URL'),
      '#maxlength' => 1024,
      '#description' => $this->t('Enter the URL you wish to scrape. It will be scraped on submission of the form.'),
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Scrape the URL'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->isValueEmpty('source')) {
      $form_state->setErrorByName('source', $this->t('You must specify a URL to scrape.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    try {
      $response = $this->httpClient->get($form_state->getValue('source'));
      $data = (string) $response->getBody();
    }
    catch (RequestException $e) {
      $this->logger('web_scraper')->error('Failed to scrape @url due to "%error".', ['@url' => $form_state->getValue('source'), '%error' => $e->getMessage()]);
      $this->drupalSetMessage($this->t('Failed to scrape the URL due to "%error".', ['%error' => $e->getMessage()]), 'error');
      return;
    }

    $this->webScraper->addArticle($data, $form_state->getValue('source'));
    $form_state->setRedirect('scraped_content.table_list');
  }

/*****************************************************************************
 **                                                                         **
 ** We use this method so that it's easy to unit test our class. For testing**
 ** we will create a helper class that extends this one. In that helper     **
 ** class we will override this method so that it's a no-op method.         **
 **                                                                         **
 *****************************************************************************/
  /**
   * Sends a message using drupal_set_message().
   *
   * @param string $message
   *   The message to send
   * @param string $type
   *   The message type
   */
  protected function drupalSetMessage($message, $type) {
    drupal_set_message($message, $type);
  }

}
