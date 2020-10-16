<?php

namespace Drupal\filters101\Plugin\Filter;

use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class MailTo.
 *
 * @package Drupal\showntell\Plugin\Filter
 *
 * @Filter(
 *   id = "mail_to",
 *   title = @Translation("Convert all Email-Addresses to MailTo links."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 *   settings = {
 *     "emphasize_mailto" = TRUE
 *   },
 *   weight = 0
 * )
 */
class MailTo extends FilterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form['emphasize_mailto'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Emphasize email addresses'),
      '#default_value' => $this->settings['emphasize_mailto'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    $result = preg_replace_callback('/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i', [$this, 'convertEmailAddress'], $text);
    return new FilterProcessResult($result);
  }

  /**
   * Helper fo Find & Transform email addresses.
   *
   * @param string $email_address
   *   Email Address.
   *
   * @return string
   *   Transformed Email Address.
   */
  public function convertEmailAddress($email_address) {
    // Convert email address to mailto link.
    $text =  '<a href="mailto:'. $email_address[0] . '">' . $email_address[0] . '</a>';

    // Emphasize email address.
    if ($this->settings['emphasize_mailto']) {
      $text = '<em>' . $text . '</em>';
    }

    return $text;
  }

  /**
   * {@inheritDoc}
   */
  public function tips($long = FALSE) {
    return $this->t('Converts email-addresses to mailto links.');
  }

}
