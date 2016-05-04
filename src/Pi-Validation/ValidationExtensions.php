<?hh

namespace Pi\Validation;

class ValidationExtensions {
    static function emailValidator() 
    {
        return new \Pi\Validation\Validators\EmailValidator();
    }

    static function minLength(int $length)
    {
        return new \Pi\Validation\Validators\MinLengthValidator($length);
    }
}