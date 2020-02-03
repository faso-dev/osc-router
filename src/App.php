<?php


namespace App;


use App\Router\Router;
use App\Router\RouterBag;

class App extends Router
{
    private $persisteRoutes = [];
    use RouterBag;
    public function __construct()
    {
        parent::__construct();
    }
}
