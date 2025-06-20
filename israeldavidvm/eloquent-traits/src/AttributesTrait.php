<?php

namespace Israeldavidvm\EloquentTraits;

use Illuminate\Support\MessageBag;

trait AttributesTrait
{
    /**
     * Validates the model's attributes.
     * This method allows you to validate the attributes that will be assigned to a model, avoiding
     * repetitive logic and dependency on Form Request Validation. This is particularly advantageous
     * when creating views with technologies like Livewire or Filament, or when data needs to be
     * validated in non-HTTP request contexts (e.g., Artisan commands, Jobs, APIs).
     *
     * @param array $arrayAttributes An associative array of attributes and their values to validate.
     * @return \Illuminate\Support\MessageBag|null Returns a `MessageBag` instance with validation errors if they occur,
     * or `null` if the validation is successful.
     */
    abstract public static function validateAttributes(array $arrayAttributes): ?MessageBag;

    /**
     * Initializes and encapsulates the attribute assignment logic for the model.
     * This method centralizes how a model's attributes are set, including any transformations,
     * calculations, or specific logic that should be applied before saving the model.
     * It helps ensure consistent initialization and keeps constructor or creation method code cleaner.
     *
     * @param array $arrayAttributes An associative array of attributes and their values to initialize the model.
     * @return void
     */
    abstract public function initAttributes(array $arrayAttributes);
}