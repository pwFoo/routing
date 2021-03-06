<?php
/* ===========================================================================
 * Copyright 2018 Zindex Software
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

trait ClosureTrait
{
    /**
     * @param array $data
     * @return array
     */
    protected function wrapClosures(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            if ($value instanceof Closure) {
                $value = SerializableClosure::from($value);
            } elseif (is_array($value)) {
                $value = $this->wrapClosures($value);
            }
            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function unwrapClosures(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            if ($value instanceof SerializableClosure) {
                $value = $value->getClosure();
            } elseif (is_array($value)) {
                $value = $this->unwrapClosures($value);
            }
            $result[$key] = $value;
        }
        return $result;
    }
}