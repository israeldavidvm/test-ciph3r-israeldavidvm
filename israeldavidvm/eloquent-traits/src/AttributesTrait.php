<?php

namespace Israeldavidvm\EloquentTraits;

use Illuminate\Support\MessageBag;

trait AttributesTrait{

    /**
     * Validate the attributes of the model
     * @param array $arrayAttributes
     * return null if validation pases, otherwise return a MessageBag with the validation errors
     */
    abstract public static function validateAttributes(array $arrayAttributes): ?MessageBag;


    abstract public function initAttributes(array $arrayAttributes);

}

?>