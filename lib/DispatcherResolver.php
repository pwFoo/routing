<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013-2016 Marius Sarca
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

class DispatcherResolver
{
    /** @var  DispatcherCollection */
    protected $collection;

    public function __construct(DispatcherCollection $collection = null)
    {
        if($collection === null){
            $collection = new DispatcherCollection();
        }
        
        $this->collection = $collection;
    }


    /**
     * @return DispatcherCollection
     */
    public function getDispatcherCollection(): DispatcherCollection
    {
        return $this->collection;
    }

    /**
     * @param Context $path
     * @param Route $route
     * @param Router $router
     * @return DispatcherInterface
     */
    public function resolve(Context $path, Route $route, Router $router): DispatcherInterface
    {
        $factory = $this->collection->get('default');
        return $factory();
    }
}
