<?php namespace Neomerx\JsonApi\Http\Headers;

/**
 * Copyright 2015-2017 info@neomerx.com
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

use \InvalidArgumentException;
use \Neomerx\JsonApi\Contracts\Http\Headers\MediaTypeInterface;

/**
 * @package Neomerx\JsonApi
 */
class MediaType implements MediaTypeInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $subType;

    /**
     * @var string
     */
    private $mediaType;

    /**
     * @var array<string,string>|null
     */
    private $parameters;

    /**
     * A list of parameter names for case-insensitive compare. Keys must be lower-cased.
     *
     * @var array
     */
    protected $ciParams = [
        'charset' => true,
    ];

    /**
     * @param string                    $type
     * @param string                    $subType
     * @param array<string,string>|null $parameters
     */
    public function __construct($type, $subType, array $parameters = null)
    {
        $type = trim($type);
        if (empty($type) === true) {
            throw new InvalidArgumentException('type');
        }

        $subType = trim($subType);
        if (empty($subType) === true) {
            throw new InvalidArgumentException('subType');
        }

        $this->type       = $type;
        $this->subType    = $subType;
        $this->mediaType  = $type . '/' . $subType;
        $this->parameters = $parameters;
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @inheritdoc
     */
    public function getSubType()
    {
        return $this->subType;
    }

    /**
     * @inheritdoc
     */
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * @inheritdoc
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @inheritdoc
     */
    public function matchesTo(MediaTypeInterface $mediaType)
    {
        return
            $this->isTypeMatches($mediaType) &&
            $this->isSubTypeMatches($mediaType) &&
            $this->isMediaParametersEqual($mediaType);
    }

    /**
     * @inheritdoc
     */
    public function equalsTo(MediaTypeInterface $mediaType)
    {
        return
            $this->isTypeEquals($mediaType) &&
            $this->isSubTypeEquals($mediaType) &&
            $this->isMediaParametersEqual($mediaType);
    }

    /**
     * Parse media type.
     *
     * @param int    $position
     * @param string $mediaType
     *
     * @return MediaType
     */
    public static function parse($position, $mediaType)
    {
        $position ?: null;

        $fields = explode(';', $mediaType);

        if (strpos($fields[0], '/') === false) {
            throw new InvalidArgumentException('mediaType');
        }

        list($type, $subType) = explode('/', $fields[0], 2);

        $parameters = null;
        $count      = count($fields);
        for ($idx = 1; $idx < $count; ++$idx) {
            if (strpos($fields[$idx], '=') === false) {
                throw new InvalidArgumentException('mediaType');
            }

            list($key, $value) = explode('=', $fields[$idx], 2);
            $parameters[trim($key)] = trim($value, ' "');
        }

        return new static($type, $subType, $parameters);
    }

    /**
     * @param MediaTypeInterface $mediaType
     *
     * @return bool
     */
    private function isTypeMatches(MediaTypeInterface $mediaType)
    {
        return $mediaType->getType() === '*' || $this->isTypeEquals($mediaType);
    }

    /**
     * @param MediaTypeInterface $mediaType
     *
     * @return bool
     */
    private function isTypeEquals(MediaTypeInterface $mediaType)
    {
        // Type, subtype and param name should be compared case-insensitive
        // https://tools.ietf.org/html/rfc7231#section-3.1.1.1
        return strcasecmp($this->getType(), $mediaType->getType()) === 0;
    }

    /**
     * @param MediaTypeInterface $mediaType
     *
     * @return bool
     */
    private function isSubTypeMatches(MediaTypeInterface $mediaType)
    {
        return $mediaType->getSubType() === '*' || $this->isSubTypeEquals($mediaType);
    }

    /**
     * @param MediaTypeInterface $mediaType
     *
     * @return bool
     */
    private function isSubTypeEquals(MediaTypeInterface $mediaType)
    {
        // Type, subtype and param name should be compared case-insensitive
        // https://tools.ietf.org/html/rfc7231#section-3.1.1.1
        return strcasecmp($this->getSubType(), $mediaType->getSubType()) === 0;
    }

    /**
     * @param MediaTypeInterface $mediaType
     *
     * @return bool
     */
    private function isMediaParametersEqual(MediaTypeInterface $mediaType)
    {
        if ($this->bothMediaTypeParamsEmpty($mediaType) === true) {
            return true;
        } elseif ($this->bothMediaTypeParamsNotEmptyAndEqualInSize($mediaType)) {
            // Type, subtype and param name should be compared case-insensitive
            // https://tools.ietf.org/html/rfc7231#section-3.1.1.1
            $ourParameters       = array_change_key_case($this->getParameters());
            $parametersToCompare = array_change_key_case($mediaType->getParameters());

            // if at least one name are different they are not equal
            if (empty(array_diff_key($ourParameters, $parametersToCompare)) === false) {
                return false;
            }

            // If we are here we have to compare values. Also some of the values should be compared case-insensitive
            // according to https://tools.ietf.org/html/rfc7231#section-3.1.1.1
            // > 'Parameter values might or might not be case-sensitive, depending on
            // the semantics of the parameter name.'
            foreach ($ourParameters as $name => $value) {
                if ($this->paramValuesEqual($name, $value, $parametersToCompare[$name]) === false) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param MediaTypeInterface $mediaType
     *
     * @return bool
     */
    private function bothMediaTypeParamsEmpty(MediaTypeInterface $mediaType)
    {
        return $this->getParameters() === null && $mediaType->getParameters() === null;
    }

    /**
     * @param MediaTypeInterface $mediaType
     *
     * @return bool
     */
    private function bothMediaTypeParamsNotEmptyAndEqualInSize(MediaTypeInterface $mediaType)
    {
        $pr1 = $this->getParameters();
        $pr2 = $mediaType->getParameters();

        return (empty($pr1) === false && empty($pr2) === false) && (count($pr1) === count($pr2));
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    private function isParamCaseInsensitive($name)
    {
        return isset($this->ciParams[$name]);
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $valueToCompare
     *
     * @return bool
     */
    private function paramValuesEqual($name, $value, $valueToCompare)
    {
        $valuesEqual = $this->isParamCaseInsensitive($name) === true ?
            strcasecmp($value, $valueToCompare) === 0 : $value === $valueToCompare;

        return $valuesEqual;
    }
}
