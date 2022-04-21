<?php

namespace Drupal\local_development\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Local development configurations.
 */
class LocalDevelopmentConfigForm extends FormBase {

  public const STATUS_CONFIG_NAME = 'local_development.is_enabled';

  public const USER_ID_CONFIG_NAME = 'local_development.active_uid';

  /**
   * The state key/value store.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  private StateInterface $state;

  /**
   * Edit html of the user guide.
   */
  public function __construct(
    StateInterface $state
  ) {
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): LocalDevelopmentConfigForm {
    return new static(
      $container->get('state')
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId(): string {
    return 'local_development_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['global_access'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Enabled Headings'),
      '#collapsible' => FALSE,
      '#tree' => TRUE,
    ];

    $form['global_access']['is_enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable session rewriting'),
      '#default_value' => (bool) $this->state->get(self::STATUS_CONFIG_NAME, ''),
    ];

    $form['global_access']['active_uid'] = [
      '#type' => 'number',
      '#title' => $this->t('Active user ID'),
      '#default_value' => $this->state->get(self::USER_ID_CONFIG_NAME, ''),
      '#description' => $this->t('Any visitor of the site will have the same permissions as the active user.'),
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $is_enabled = (bool) $form_state->getValue(['global_access', 'is_enabled']);
    $active_uid = (string) $form_state->getValue(['global_access', 'active_uid']);
    $this->state->set(self::STATUS_CONFIG_NAME, $is_enabled);
    $this->state->set(self::USER_ID_CONFIG_NAME, $active_uid);
  }

}
