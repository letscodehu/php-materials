<?php 

class Form {

    public $fields = [];

}

interface Validator {

    /**
    *  Returns true if the given form is valid, false otherwise.
    */
    function validate(Form $form);

}

class OrderController {

    private $validator;

    public function __construct(Validator $validator) {
        $this->validator = $validator;
    }

    public function submit(Form $form) {
        if ($this->validator->validate($form)) {
            echo 'Valid!'.PHP_EOL;
        } else {
            echo 'Invalid!'.PHP_EOL;
        }
    }

}

class CompositeValidator implements Validator {

    private $validators = [];

    public function validate(Form $form) {
        echo 'CompositeValidator'. PHP_EOL;
        $isValid = true;
        foreach ($this->validators as $validator) {
            if (!$validator->validate($form)) {
                $isValid = false;
              //  break;
            }
        }
        return $isValid;
    }

    public function add(Validator $validator) {
        $this->validators[] = $validator;
        return $this;
    }
}


class NonEmptyValidator implements Validator {

    public function validate(Form $form) {
        echo 'NonEmptyValidator'. PHP_EOL;
        return !empty($form->fields);
    }

}

class EmailFieldValidator implements Validator {
    
    public function validate(Form $form) {
        echo 'EmailFieldValidator'. PHP_EOL;
        return array_key_exists("email", $form->fields);
    }

}

$nestedComposite = new CompositeValidator();
$nestedComposite->add(new NonEmptyValidator())->add(new EmailFieldValidator());
$compositeValidator = new CompositeValidator();
$compositeValidator->add(new NonEmptyValidator())
    ->add(new EmailFieldValidator())->add($nestedComposite);

$controller = new OrderController($compositeValidator);
$controller->submit(new Form());