<?php

class Request
{
    private $headers = [];

    public function getHeaders()
    {
        return $this->headers;
    }
}

class Response
{
    private $headers = [];

    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }
}

interface Filter
{
    public function handle(Request $request, Response $response);
}

abstract class BaseFilter implements Filter
{
    protected $next;

    public function setNext(Filter $next)
    {
        $this->next = $next;
    }

    protected function callNext(Request $request, Response $response)
    {
        if ($this->next != null) {
            $this->next->handle($request, $response);
        }
    }
}

class FilterChain
{
    private $filters = [];

    public function addFilter(Filter $filter)
    {
        $lastFilter = end($this->filters);
        if ($lastFilter != null) {
            $lastFilter->setNext($filter);
        }
        $this->filters[] = $filter;
    }

    public function doFilter(Request $request, Response $response)
    {
        reset($this->filters)->handle($request, $response);
    }
}

class SomeRequestFilter extends BaseFilter
{
    public function handle(Request $request, Response $response)
    {
        echo 'Called the CSRF filter'. PHP_EOL;
        $requestHeaders = $request->getHeaders();
        if (array_key_exists("XSRF-TOKEN", $requestHeaders)) {
            $this->callNext($request, $response);
        }
    }
}

class SomeResponseFilter extends BaseFilter
{
    public function handle(Request $request, Response $response)
    {
        echo 'Called the response filter'. PHP_EOL;
        $response->addHeader("X-REQUESTED-BY", "wat");
        $this->callNext($request, $response);
    }
}

$chain = new FilterChain;
$response = new Response;
$request = new Request;
$chain->addFilter(new SomeRequestFilter);
$chain->addFilter(new SomeResponseFilter);
$chain->addFilter(new SomeResponseFilter);
$chain->doFilter($request, $response);
var_dump($request, $response);
