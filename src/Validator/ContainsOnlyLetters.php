<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ContainsOnlyLetters extends Constraint
{
    public $message = 'Value "{{ string }}" can only contain letters.';
}