<?php

class Validator
{
    private bool $passed = false;
    private array $errors = [];
    private null|DB $db = null;

    public function __construct()
    {
        $this->db = DB::getInstanse();
    }

    public function check($source, $items = [])
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {

                $value = $source[$item];

                if ($rule == 'required' && empty($value)) {
                    $this->addError("{$item} не заполнено");
                } else if (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rule_value) {
                                $this->addError("{$item} поле должно быть не меньше {$rule_value} символов");
                            }
                            break;
                        case 'max':
                            if (strlen($value) > $rule_value) {
                                $this->addError("{$item} не должен превышать {$rule_value} символов");
                            }
                            break;
                        case 'matches':
                            if ($value != $source[$rule_value]) {
                                $this->addError("{$rule_value} должен совпадать с {$item}");
                            }
                            break;
                        case 'unique':
                            $check = $this->db->getForTable($rule_value, [(string)$item, '=', (string)$value]);
                            if ($check->getCount()) {
                                $this->addError("{$item} уже существует");
                            }
                            break;
                            ///можем добавлять любое правило для валидации
                        case 'email':
                            if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
                                $this->addError("{$item} не является email-ом");
                            }
                            break;
                    }

                }//else $this->passed = true;
            }
        }
        if (empty($this->errors)){
            $this->passed = true;
        }
    }

    public function addError($error)
    {
        $this->errors[] = $error;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function passed(): bool
    {
        return $this->passed;
    }
}
