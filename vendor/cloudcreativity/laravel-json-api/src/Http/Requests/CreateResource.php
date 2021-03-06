<?php
/**
 * Copyright 2020 Cloud Creativity Limited
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

namespace CloudCreativity\LaravelJsonApi\Http\Requests;

use CloudCreativity\LaravelJsonApi\Contracts\Validation\ValidatorFactoryInterface;
use CloudCreativity\LaravelJsonApi\Contracts\Validators\ValidatorProviderInterface;
use CloudCreativity\LaravelJsonApi\Exceptions\ValidationException;
use CloudCreativity\LaravelJsonApi\Object\Document;

/**
 * Class CreateResource
 *
 * @package CloudCreativity\LaravelJsonApi
 */
class CreateResource extends ValidatedRequest
{

    /**
     * @inheritDoc
     */
    protected function authorize()
    {
        if (!$authorizer = $this->getAuthorizer()) {
            return;
        }

        $authorizer->create($this->getType(), $this->request);
    }

    /**
     * @inheritDoc
     */
    protected function validateQuery()
    {
        if (!$validators = $this->getValidators()) {
            return;
        }

        /** Pre-1.0 validators */
        if ($validators instanceof ValidatorProviderInterface) {
            $validators->resourceQueryChecker()->checkQuery($this->getEncodingParameters());
            return;
        }

        /** 1.0 validators */
        $this->passes(
            $validators->modifyQuery($this->query())
        );
    }

    /**
     * @inheritDoc
     */
    protected function validateDocument()
    {
        $document = $this->decode();
        $validators = $this->getValidators();

        /** Pre-1.0 validators */
        if ($validators instanceof ValidatorProviderInterface) {
            $this->validateDocumentWithProvider($validators, $document);
            return;
        }

        /** If there is a decoded JSON API document, check it complies with the spec. */
        if ($document) {
            $this->validateDocumentCompliance($document, $validators);
        }

        /** Check the document is logically correct. */
        if ($validators) {
            $this->passes($validators->create($this->all()));
        }
    }

    /**
     * Validate the JSON API document complies with the spec.
     *
     * @param object $document
     * @param ValidatorFactoryInterface|null $validators
     */
    protected function validateDocumentCompliance($document, ?ValidatorFactoryInterface $validators): void
    {
        $this->passes(
            $this->factory->createNewResourceDocumentValidator(
                $document,
                $this->getResourceType(),
                $validators && $validators->supportsClientIds()
            )
        );
    }

    /**
     * @param ValidatorProviderInterface $validators
     * @param $document
     * @deprecated 2.0.0
     */
    protected function validateDocumentWithProvider(ValidatorProviderInterface $validators, $document)
    {
        $validator = $validators->createResource();

        if (!$validator->isValid(new Document($document))) {
            throw new ValidationException($validator->getErrors());
        }
    }

}
