<?php
 namespace Respect\Validation\Exceptions\Locale; use Respect\Validation\Exceptions\ValidationException; class PlIdentityCardException extends ValidationException { public static $defaultTemplates = [ self::MODE_DEFAULT => [ self::STANDARD => '{{name}} must be a valid Polish Identity Card number', ], self::MODE_NEGATIVE => [ self::STANDARD => '{{name}} must not be a valid Polish Identity Card number', ], ]; } 