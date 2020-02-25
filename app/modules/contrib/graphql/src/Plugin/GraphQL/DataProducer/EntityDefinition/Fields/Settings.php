<?php

namespace Drupal\graphql\Plugin\GraphQL\DataProducer\EntityDefinition\Fields;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\graphql\Plugin\GraphQL\DataProducer\DataProducerPluginBase;

/**
 * Retrieves the "settings" from a given field definition.
 *
 * @DataProducer(
 *   id = "entity_definition_field_settings",
 *   name = @Translation("Entity definition field settings"),
 *   description = @Translation("Return entity definition field settings."),
 *   consumes = {
 *     "entity_definition_field" = @ContextDefinition("any",
 *       label = @Translation("Entity definition field")
 *     ),
 *     "entity_form_display_context" = @ContextDefinition("any",
 *       label = @Translation("Entity form display context"),
 *       required = FALSE,
 *     )
 *   },
 *   produces = @ContextDefinition("string",
 *     label = @Translation("Entity definition field settings")
 *   )
 * )
 */
class Settings extends DataProducerPluginBase {

  /**
   * Resolves the field settings.
   *
   * @param \Drupal\Core\Field\FieldDefinitionInterface $entity_definition_field
   *   The entity field definition.
   * @param array|null $entity_form_display_context
   *   Entity form display context.
   *
   * @return array
   *   Field settings.
   */
  public function resolve(
    FieldDefinitionInterface $entity_definition_field,
    ?array $entity_form_display_context = NULL
  ): \Iterator {
    $settings = $entity_definition_field->getSettings();

    if ($entity_form_display_context) {
      $entity_form_display = $entity_form_display_context['key'];
      $content = $entity_form_display->get('content');
      $field_id = $entity_definition_field->getName();
      if (isset($content[$field_id])) {
        $form_settings = $content[$field_id]['settings'];
        $settings['form_settings'] = $form_settings;
      }
    }

    foreach ($settings as $key => $value) {
      yield [
        'key' => $key,
        'value' => $value,
      ];
    }
  }

}
