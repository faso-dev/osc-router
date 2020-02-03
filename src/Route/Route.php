<?php


namespace App\Route;


class Route
{
    /**
     * @var string
     */
    private $path;
    /**
     * @var mixed
     */
    private $callable;
    /**
     * @var string
     */
    private $name;
    /**
     * @var array
     */
    private $matches = [];
    /**
     * @var array
     */
    private $params = [];
    /**
     * @var string
     */
    private $url;

    public function __construct(string $url, string $path, $callable, ?string $name = null)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
        $this->name = $name;
        $this->url = $url;
    }

    public function match(string $url)
    {

        return true;
    }

    /**
     * @return array
     */
    public function getMatches(): ?array
    {
        return $this->matches();
    }

    /**
     * @return mixed
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * @return array|null
     */
    private function matches(): ?array
    {
        $url = trim($this->url, '/');
        $path = preg_replace('#{([\w]+)}#', '([^/]+)', $this->path);
        $regex = "#^$path$#i";
        if (!preg_match($regex, $url, $matches)){
            return null;
        }
        array_shift($matches);
        return $this->matches = $matches;
    }
    /**
     * @param array $matches
     * @return Route
     */
    public function setMatches(array $matches): Route
    {
        $this->matches = $matches;
        return $this;
    }

    /**
     * @param string $path
     * @return Route
     */
    public function setPath(string $path): Route
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $url
     * @return Route
     */
    public function setUrl(string $url): Route
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    private function with(string $param, string $constraint)
    {
        $this->params[$param] = $constraint;
        return $this;
    }

    private function paramsMatch(array $match)
    {
         if(isset($this->params[$match[1]])){
             return '(' .$this->params[$match[1]]. ')';
         }
         return '([^/]+)';
    }

    /**
     * Renvoie l'url générée
     * @param $params
     * @return string
     */
    public function generateUrl(array $params): string
    {
        $path = $this->path;
        foreach ($params as $k => $v){
            $path = str_replace("{".$k."}", $v, $path);
        }
        return $path;
    }
}
