<?php

namespace hollis1024\lumen\struct\Traits;


trait ModelAttributes
{
    /**
     * @param null $names
     * @return array
     * @throws \ReflectionException
     */
    public function getAttributes($names = null)
    {
        $values = [];
        if ($names === null) {
            $names = $this->attributes();
        }
        foreach ($names as $name) {
            $values[$name] = $this->$name;
        }
        return $values;
    }

    /**
     * @param $values
     * @throws \ReflectionException
     */
    public function setAttributes($values) {
        if (is_array($values)) {
            foreach ($values as $name => $value) {
                $this->hasAttribute($name) && $this->setAttribute($name, $value);
            }
        }
    }

    /**
     * @param string $name
     * @return mixed|null
     * @throws \ReflectionException
     */
    public function getAttribute($name)
    {
        $attributes = $this->getAttributes([$name]);
        return isset($attributes[$name]) ? $attributes[$name] : null;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed|void
     * @throws \ReflectionException
     */
    public function setAttribute($name, $value)
    {
        if ($this->hasAttribute($name)) {
            $setter = 'set' . $name;
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            } else {
                $this->$name = $value;
            }
        } else {
            throw new \InvalidArgumentException(get_class($this) . ' has no attribute named "' . $name . '".');
        }
    }


    /**
     * @return array
     * @throws \ReflectionException
     */
    public function attributes()
    {
        $class = new \ReflectionClass($this);
        $names = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $names[] = $property->getName();
            }
        }

        return $names;
    }

    /**
     * @param $name
     * @return bool
     * @throws \ReflectionException
     */
    public function hasAttribute($name)
    {
        $attributes = $this->attributes();
        return isset($attributes[$name]) || in_array($name, $attributes, true);
    }
}