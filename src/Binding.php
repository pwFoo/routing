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

class Binding
{
    /** @var mixed|null  */
    protected $value;

    /** @var array|null */
    protected $arguments;

    /** @var callable|null */
    protected $callback;

    /**
     * Binding constructor.
     * @param callable|null $callback
     * @param array|null $arguments
     * @param mixed|null $value
     */
    public function __construct(callable $callback = null, array $arguments = null, $value = null)
    {
        $this->callback = $callback;
        $this->arguments = $arguments;
        $this->value = $value;
    }

    /**
     * @return mixed|null
     */
    public function value()
    {
        if($this->value === null && $this->callback !== null) {
            $callback = $this->callback;
            $arguments = $this->arguments ?? array();
            $this->value = $callback(...$arguments);
        }
        
        return $this->value;
    }
}