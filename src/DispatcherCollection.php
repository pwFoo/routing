<?php
/* ===========================================================================
 * Copyright 2013-2016 The Opis Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================================ */

namespace Opis\Routing;

use Closure;
use Opis\Closure\SerializableClosure;
use Serializable;

class DispatcherCollection implements Serializable
{
    /** @var callable[] */
    protected $factories = [];

    /**
     * @param string $name
     * @param callable $factory
     * @return DispatcherCollection
     */
    public function register(string $name, callable $factory): self
    {
        $this->factories[$name] = $factory;
        return $this;
    }

    /**
     * @param string $name
     * @return callable
     */
    public function get(string $name): callable
    {
        return $this->factories[$name] ?? $this->defaultDispatcher();
    }

    /**
     * @return callable
     */
    public function defaultDispatcher(): callable
    {
        return function () {
            return new Dispatcher();
        };
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        SerializableClosure::enterContext();

        $object = serialize(array_map(function($value){
            return $value instanceof Closure ? SerializableClosure::from($value) : $value;
        }, $this->factories));

        SerializableClosure::exitContext();

        return $object;
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        $this->factories = array_map(function($value){
            return $value instanceof SerializableClosure ? $value->getClosure() : $value;
        }, unserialize($serialized));
    }

}