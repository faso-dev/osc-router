<?php


namespace App\Router;

use App\Exception\BadAppMethodCall;
use App\Exception\NotFoundRouteException;
use App\Route\Route;

class Router
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var array
     */
    private $routes = [];
    /**
     * @var string
     */
    private $path;
    /**
     * @var Route []
     */
    protected $nameRoutes = [];
    /**
     * @var mixed
     */
    public function __construct()
    {
        $this->url = $_GET['url'];
    }

    /**
     * @param string $path
     * @param $callable
     * @param string $name
     * @return Router
     */
    public function get(string $path, $callable, string $name = null)
    {
        $this->add($path, $callable, 'GET', $name);
        return $this;
    }

    /**
     * @param string $path
     * @param $callable
     * @param string $name
     * @return Router
     */
    public function post(string $path, $callable, string $name = null)
    {
       $this->add($path, $callable, 'POST',$name);
       return $this;
    }

    public function put(string $path, $callable, string $name = null)
    {
        $this->add($path, $callable, 'PUT',$name);
        return $this;
    }

    public function delete(string $path, $callable, string $name = null)
    {
        $this->add($path, $callable, 'PUT',$name);
        return $this;
    }

    private function add(string $path, $callable, string $method, string $name = null)
    {
        /** @var Route $route */
        $route = new Route($this->url,$path, $callable, $name);
        $this->routes[$method][] = $route;
        if($name !== null){
            $this->nameRoutes[$name] = $route;
        }
    }
    /**
     * @return mixed
     * @throws BadAppMethodCall
     * @throws NotFoundRouteException
     */
    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if (!isset($method)){
            throw new BadAppMethodCall(sprintf('No method matched %s ', $method));
        }
        foreach ($this->routes[$method] as $route){
            if ($this->match($route)){
                return $this->call($route);
            }
        }
        throw new NotFoundRouteException(sprintf('The route %s does not exits', $this->url));
    }

    /**
     * @param Route $route
     * @return mixed
     */
    private function call(Route $route)
    {
        if (is_string($route->getCallable())){
            $callable = explode("@", $route->getCallable());
            $controller = 'App\\Controller\\'.$callable[0];
            $method = $callable[1];
           return (new $controller())->$method();
        }else{
            return call_user_func_array($route->getCallable(), $route->getMatches());
        }

    }

    /**
     * @param Route $route
     * @return bool
     */
    private function match(Route $route)
    {
        return $route->getMatches() !== null;
    }

}
