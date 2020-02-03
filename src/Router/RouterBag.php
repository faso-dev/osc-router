<?php


namespace App\Router;


use App\Exception\NotFoundRouteException;

trait RouterBag
{
    /**
     * @param $data
     */
    public function setRoutes(array $data)
    {
        $this->persisteRoutes = $data;
    }

    /**
     * @param string $route_name
     * @param null $params
     * @return string
     * @throws NotFoundRouteException
     */
    public function path(string $route_name, $params = null)
    {
        if (isset($this->nameRoutes[$route_name])){
            return $this->nameRoutes[$route_name]->generateUrl($params);
        }
        throw new NotFoundRouteException(sprintf('No route match %s ', $route_name));
    }

    /**
     * @param array|null $routes
     */
    public function enable(?array $routes = null)
    {
        if (null === $routes){
            foreach (array_chunk($this->persisteRoutes, 50) as $key => $item){
                $method = strtolower($item[$key]['http_method']);
                $path = strtolower($item[$key]['path']);
                $callable = strtolower($item[$key]['controller']);
                $this->$method($path,$callable,$key);
            }
        }
        else{
            foreach ($routes as $route_name){
                $method = strtolower($this->persisteRoutes[$route_name]['http_method']);
                $path = strtolower($this->persisteRoutes[$route_name]['path']);
                $callable = $this->persisteRoutes[$route_name]['controller'];
                $this->$method($path,$callable,$route_name);
            }
        }
    }

}
