<?php

interface Validator {

    /**
    * Returns true if the given input is valid, false otherwise.
    * @throws InvalidArgumentException when $value is null.
    */
    public function validate($value);

}

class EmailValidationException extends InvalidArgumentException {

}

class EmailValidator implements Validator {

    /**
    * Returns true if the given email is valid, false otherwise.
    * @throws EmailValidationException when email is null.
    */
    public function validate($email) {
        if ($email == null) {
            throw new EmailValidationException("The given email cannot be empty");
        }
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

}

class LengthAwareEmailValidator extends EmailValidator {
    
    /**
    * Returns true if the given email is valid, false otherwise.
    * @throws EmailValidationException when email is null or shorter than 5 characters.
    */
    public function validate($email) {
        if ($email == null) {
            throw new EmailValidationException("The given email cannot be empty");
        } elseif (strlen($email) < 6) {
            throw new EmailValidationException("The given email cannot be shorter than 5 characters");
        }
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

}


class RegisterController {

    private $validator;

    public function __construct(Validator $validator) {
        $this->validator = $validator;
    }

    public function handlePost($email) {
        try {
            if ($this->validator->validate($email)) {
                echo 'Valid e-mail'. PHP_EOL;
            } else {
                echo 'Invalid e-mail'. PHP_EOL;
            }
        } catch (InvalidArgumentException $e) {
            echo 'Exception caught!'. PHP_EOL;
        }
        
    }

}

$controller = new RegisterController(new LengthAwareEmailValidator);
$controller->handlePost(null);