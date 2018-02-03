<?php
/* ===========================================================================
 * Copyright 2013-2018 The Opis Project
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

use SplObjectStorage;

class Router
{
    /** @var RouteCollection */
    protected $routes;

    /** @var FilterCollection */
    protected $filters;

    /** @var Dispatcher */
    protected $dispatcher;

    /** @var array */
    protected $global;

    /** @var array */
    protected $compacted = [];

    /** @var Context|null */
    protected $context;

    /**
     * Router constructor.
     * @param RouteCollection $routes
     * @param IDispatcher|null $dispatcher
     * @param FilterCollection|null $filters
     * @param GlobalValues|null $global
     */
    public function __construct(
        RouteCollection $routes,
        IDispatcher $dispatcher = null,
        FilterCollection $filters = null,
        GlobalValues $global = null
    )
    {
        if ($dispatcher === null) {
            $dispatcher = new Dispatcher();
        }
        $this->routes = $routes;
        $this->dispatcher = $dispatcher;
        $this->filters = $filters;
        $this->global = $global;
    }

    /**
     * Get the route collection
     *
     * @return  RouteCollection
     */
    public function getRouteCollection(): RouteCollection
    {
        return $this->routes;
    }

    /**
     * Get the filter collection
     *
     * @return  FilterCollection
     */
    public function getFilterCollection(): FilterCollection
    {
        if ($this->filters === null) {
            $this->filters = new FilterCollection();
        }
        return $this->filters;
    }

    /**
     * Get global values
     *
     * @return  GlobalValues
     */
    public function getGlobalValues(): GlobalValues
    {
        if ($this->global === null) {
            $this->global = new GlobalValues();
        }
        return $this->global;
    }

    /**
     * Get the dispatcher resolver
     *
     * @return IDispatcher
     */
    public function getDispatcher(): IDispatcher
    {
        return $this->dispatcher;
    }

    /**
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }

    /**
     * @param Route $route
     * @return CompactRoute
     */
    public function compact(Route $route)
    {
        $cid = spl_object_hash($this->context);
        $rid = spl_object_hash($route);

        if (!isset($this->compacted[$cid][$rid])) {
            return $this->compacted[$cid][$rid] = new CompactRoute($route, $this->context, $this->getGlobalValues());
        }

        return $this->compacted[$cid][$rid];
    }

    /**
     *
     * @param   Context $context
     *
     * @return  mixed
     */
    public function route(Context $context)
    {
        $this->context = $context;
        return $this->getDispatcher()->dispatch($this);
    }
}
