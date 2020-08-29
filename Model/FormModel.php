<?php

namespace hollis1024\LumenStruct\Model;

use hollis1024\LumenStruct\BaseObject;
use hollis1024\LumenStruct\Exceptions\InvalidParameterException;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class FormModel extends BaseObject
{
    use ModelAttributes;

    /**
     * @var array
     */
    private $errors;


    protected function rules() {
        return [];
    }

    protected function messages() {
        return [];
    }

    /**
     * @param $data
     * @return bool
     * @throws \ReflectionException
     */
    public function load($data) {
        if (!empty($data) && is_array($data)) {
            $this->setAttributes($data);

            return true;
        }

        return false;
    }


    /**
     * @param bool $throwable
     * @return bool
     * @throws \ReflectionException
     */
    public function validate($throwable = false) {
        /* @var $validatorFactory Factory */
        $validatorFactory = app('validator');
        /* @var $validator Validator */
        $validator = $validatorFactory->make($this->getAttributes(), $this->rules(), $this->messages());
        if ($validator->fails()) {
            if ($throwable) {
                $validationException = new ValidationException($validator);
                throw new InvalidParameterException($validationException->validator->errors()->first());
            }
            $this->setErrors($validator->errors()->getMessages());
        }

        return !$this->hasError();
    }


    /**
     * @param null|string $attribute
     * @return bool
     */
    public function hasError($attribute = null) {
        return $attribute === null ? !empty($this->getErrors()) : isset($this->errors[$attribute]);
    }

    /**
     * @return mixed
     */
    public function getErrors() {
        return $this->errors === null ? [] : $this->errors;
    }

    /**
     * @param array $errors
     */
    protected function setErrors(array $errors) {
        $this->errors = $errors;
    }

    /**
     * @param $name
     * @return mixed|null
     * @throws \ReflectionException
     */
    public function __get($name)
    {
        return $this->getAttribute($name);
    }

    /**
     * @param $name
     * @param $value
     * @throws \ReflectionException
     */
    public function __set($name, $value)
    {
        $this->setAttribute($name, $value);
    }

}