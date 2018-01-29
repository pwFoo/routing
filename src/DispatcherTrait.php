<?php
/* ===========================================================================
 * Copyright 2013-2017 The Opis Project
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

trait DispatcherTrait
{
    /**
     * @param Router $router
     * @param Context $context
     * @return null|Route
     */
    protected function findRoute(Router $router, Context $context)
    {
        $global = $router->getGlobalValues();
        $global['router'] = $router;
        $global['context'] = $context;
        /** @var Route $route */
        foreach ($this->match($router, $context) as $route){
            $global['route'] = $route;
            if(!$this->filter($router, $context, $route)){
                continue;
            }
            return $route;
        }
        $global['route'] = null;
        return null;
    }

    /**
     * @param Router $router
     * @param Context $context
     * @return \Generator
     */
    protected function match(Router $router, Context $context): \Generator
    {
        $context = (string) $context;
        $routes = $router->getRouteCollection();

        foreach ($routes->getRegexPatterns() as $routeID => $pattern){
            if(preg_match($pattern, $context)){
                yield $routes->getRoute($routeID);
            }
        }
    }

    /**
     * @param Router $router
     * @param Context $context
     * @param Route $route
     * @return bool
     */
    protected function filter(Router $router, Context $context, Route $route): bool
    {
        foreach ($router->getFilterCollection()->getFilters() as $filter){
            if(!$filter->filter($router, $context, $route)){
                return false;
            }
        }

        return true;
    }
}