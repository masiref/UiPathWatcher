<?php

/**
 * Copyright 2017 Cloud Creativity Limited
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace CloudCreativity\Utils\Object;

use InvalidArgumentException;
use stdClass;
use Traversable;

/**
 * Class ObjectUtils
 *
 * @package CloudCreativity\Utils\Object
 */
class Obj
{

    /**
     * @param StandardObjectInterface|object|null $data
     * @return StandardObjectInterface
     */
    public static function cast($data)
    {
        return ($data instanceof StandardObjectInterface) ? $data : new StandardObject($data);
    }

    /**
     * @param $json
     * @param int $depth
     * @param int $options
     * @return StandardObject|null
     */
    public static function decode($json, $depth = 512, $options = 0)
    {
        $data = json_decode($json, false, $depth, $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new DecodeException();
        }

        return is_object($data) ? new StandardObject($data) : null;
    }

    /**
     * @param object $data
     * @param string $key
     * @param mixed $default
     * @return StandardObjectInterface|mixed
     */
    public static function get($data, $key, $default = null)
    {
        if ($data instanceof StandardObjectInterface) {
            return $data->get($key, $default);
        }

        if (!property_exists($data, $key)) {
            return $default;
        }

        $value = $data->{$key};

        return is_object($value) ? static::cast($value) : $value;
    }

    /**
     * Clone the object recursively.
     *
     * @param object $data
     * @return object
     */
    public static function replicate($data)
    {
        $copy = clone $data;

        foreach ($copy as $key => $value) {
            if (is_object($value)) {
                $copy->{$key} = static::replicate($value);
            }
        }

        return $copy;
    }

    /**
     * @param object|array $data
     * @return Traversable
     */
    public static function traverse($data)
    {
        foreach ($data as $key => $value) {
            yield $key => is_object($value) ? static::cast($value) : $value;
        }
    }

    /**
     * @param object|array $data
     * @return array
     */
    public static function toArray($data)
    {
        if (!is_object($data) && !is_array($data)) {
            throw new InvalidArgumentException('Expecting an object or array to convert to an array.');
        }

        $arr = [];

        foreach ($data as $key => $value) {
            $arr[$key] = (is_object($value) || is_array($value)) ? static::toArray($value) : $value;
        }

        return $arr;
    }

    /**
     * @param object|array $data
     * @param callable $transform
     * @return array|stdClass
     */
    public static function transformKeys($data, callable $transform)
    {
        if (!is_object($data) && !is_array($data)) {
            throw new InvalidArgumentException('Expecting an object or array to transform keys.');
        }

        $copy = is_object($data) ? clone $data : $data;

        foreach ($copy as $key => $value) {

            $transformed = call_user_func($transform, $key);
            $value = (is_object($value) || is_array($value)) ? self::transformKeys($value, $transform) : $value;

            if (is_object($data)) {
                unset($data->{$key});
                $data->{$transformed} = $value;
            } else {
                unset($data[$key]);
                $data[$transformed] = $value;
            }
        }

        return $data;
    }
}
