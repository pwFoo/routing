<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013 Marius Sarca
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

abstract class Router
{
    
    protected $collection;
    
    public function __construct(RouteCollection $collection)
    {
        $this->collection = $collection;
    }
    
    public function getCollection()
    {
        return $this->collection;
    }
    
    public function execute()
    {
        foreach($this->collection as $route)
        {
            if($this->match($route))
            {
                return $this->dispatcher()->dispatch($route);
            }
        }
    }
    
    protected function match(Route $route)
    {
        foreach($this->filters() as $filter)
        {
            if(!$filter->match($route))
            {
                return false;
            }
        }
        return true;
    }
    
    protected abstract function dispatcher();
    
    protected abstract function filters();
}