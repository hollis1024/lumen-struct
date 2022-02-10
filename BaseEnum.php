<?php

namespace hollis1024\lumen\struct;

use \ReflectionClass;
use \ReflectionException;

class BaseEnum
{
    const __default = null;

    /**
     * @var string
     */
    protected static $value;

    /**
     * @var ReflectionClass
     */
    protected static $reflectionClass;


    /**
     * 将构造函数的 修饰符改成了 受保护的 即 外部无法直接 new
     * BaseEnum constructor.
     * @param null $value
     */
    protected function __construct($value = null)
    {
        self::$value = is_null($value) ? static::__default : $value;
    }

    /**
     * @param $name
     * @param $arguments
     * @return BaseEnum
     * @throws ReflectionException
     */
    public static function __callStatic($name, $arguments)
    {
        $reflectionClass = self::getReflectionClass();
        $constant = $reflectionClass->getConstant(strtoupper($name));
        $construct = $reflectionClass->getConstructor();
        $construct->setAccessible(true);
        $static = new static($constant);
        return $static;

    }

    /**
     * 实例化一个反射类
     * @return ReflectionClass
     * @throws ReflectionException
     */
    protected static function getReflectionClass()
    {
        if (!self::$reflectionClass instanceof ReflectionClass) {
            self::$reflectionClass = new ReflectionClass(static::class);
        }
        return self::$reflectionClass;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)self::$value;
    }

    /**
     * 判断一个值是否有效 即是否为枚举成员的值
     * @param $val
     * @return bool
     * @throws ReflectionException
     */
    public static function isValid($val)
    {
        return in_array($val, self::toArray());
    }

    /**
     * 转换枚举成员为键值对输出
     * @return array
     * @throws ReflectionException
     */
    public static function toArray()
    {
        return self::getEnumMembers();
    }

    /**
     * 获取枚举的常量成员数组
     * @return array
     * @throws ReflectionException
     */
    public static function getEnumMembers()
    {
        return self::getReflectionClass()
            ->getConstants();
    }

    /**
     * 获取枚举成员值数组
     * @return array
     * @throws ReflectionException
     */
    public static function values()
    {
        return array_values(self::toArray());
    }

    /**
     * 获取枚举成员键数组
     * @return array
     * @throws ReflectionException
     */
    public static function keys()
    {
        return array_keys(self::getEnumMembers());
    }

    /**
     * 判断 Key 是否有效 即存在
     * @param $key
     * @return bool
     * @throws ReflectionException
     */

    public static function isKey($key)
    {
        return in_array($key, array_keys(self::getEnumMembers()));
    }

    /**
     * 根据 Key 去获取枚举成员值
     * @param $key
     * @return static
     */
    public static function getKey($key)
    {
        return self::$key();
    }

    /**
     * 格式枚举结果类型
     * @param null|bool|int $type 当此处的值时什么类时 格式化输出的即为此类型
     * @return bool|int|string|null
     */
    public function format($type = null)
    {
        switch (true) {
            case ctype_digit(self::$value) || is_int($type):
                return (int)self::$value;
                break;
            case $type === true:
                return (bool)filter_var(self::$value, FILTER_VALIDATE_BOOLEAN);
                break;
            default:
                return self::$value;
                break;

        }

    }
}