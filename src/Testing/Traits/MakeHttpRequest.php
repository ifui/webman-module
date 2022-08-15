<?php

namespace Ifui\WebmanModule\Testing\Traits;

use FastRoute\Dispatcher;
use support\Request;
use Webman\Config;
use Webman\Http\Response;
use Webman\Route;
use function is_array;

trait MakeHttpRequest
{

    /**
     * Additional headers for the request.
     *
     * @var array
     */
    protected $defaultHeaders = [];

    /**
     * Additional cookies for the request.
     *
     * @var array
     */
    protected $defaultCookies = [];

    /**
     * Add an authorization token for the request.
     *
     * @param string $token
     * @param string $type
     * @return $this
     */
    public function withToken(string $token, string $type = 'Bearer')
    {
        return $this->withHeader('Authorization', $type . ' ' . $token);
    }

    /**
     * Add a header to be sent with the request.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function withHeader(string $name, string $value)
    {
        $this->defaultHeaders[$name] = $value;

        return $this;
    }

    /**
     * Flush all the configured headers.
     *
     * @return $this
     */
    public function flushHeaders()
    {
        $this->defaultHeaders = [];

        return $this;
    }

    /**
     * Visit the given URI with a GET request.
     *
     * @param string $uri
     * @param array $parameters
     * @param array $headers
     * @return Response
     */
    public function get($uri, $parameters = [], $headers = [])
    {
        $this->withHeaders($headers);
        return $this->call('GET', $uri, $parameters);
    }

    /**
     * Define additional headers to be sent with the request.
     *
     * @param array $headers
     * @return $this
     */
    public function withHeaders(array $headers)
    {
        $this->defaultHeaders = array_merge($this->defaultHeaders, $headers);

        return $this;
    }

    /**
     * Call the given URI and return the Response.
     *
     * @param string $method
     * @param string $uri
     * @param array $parameters
     * @return Response
     */
    protected function call($method, $uri, $parameters = [])
    {
        $ret = Route::dispatch($method, $uri);

        if ($ret[0] === Dispatcher::FOUND) {
            $request = $this->request($method, $uri, $parameters);
            $callback = $ret[1]['callback'];
            $route = $ret[1]['route'];
            $route = clone $route;
            $args = !empty($ret[2]) ? $ret[2] : null;
            if ($args) {
                $route->setParams($args);
            }
            if (is_array($callback) && isset($callback[0])) {
                /** @var Response $res */
                return call_user_func([new $callback[0], $callback[1]], $request, $args);
            }
        } else {
            return new Response(404, [], '404');
        }
    }

    /**
     * Mock send request.
     *
     * @param $method
     * @param $uri
     * @param $parameters
     * @return mixed
     */
    public function request($method, $uri, $parameters = [])
    {
        $requestClassName = Config::get('app.request_class', Request::class);
        $request = new $requestClassName('');
        $request->_data = [
            'post' => $parameters,
            'get' => $parameters,
            'headers' => $this->defaultHeaders,
            'cookie' => $this->defaultCookies,
            'files' => [], // TODO
            'method' => $method,
            'protocolVersion' => '1.1',
            'host' => 'localhost',
            'uri' => $uri,
        ];
        return $request;
    }

    /**
     * Visit the given URI with a GET request, expecting a JSON response.
     *
     * @param string $uri
     * @param array $headers
     * @return Response
     */
    public function getJson($uri, $parameters = [], $headers = [])
    {
        return $this->json('GET', $uri, $parameters, $headers);
    }

    /**
     * Call the given URI with a JSON request.
     *
     * @param string $method
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return Response
     */
    public function json($method, $uri, $data = [], $headers = [])
    {
        $content = json_encode($data);

        $this->withHeaders([
            'CONTENT_LENGTH' => mb_strlen($content, '8bit'),
            'CONTENT_TYPE' => 'application/json',
            'Accept' => 'application/json',
        ]);

        $rsp = $this->call($method, $uri, $data);
        $body = $rsp->rawBody();
        $json = json_decode($body, true) ?? $body;
        $rsp->withBody($json);
        return $rsp;
    }
}