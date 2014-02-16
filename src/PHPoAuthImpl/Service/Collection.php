<?php

namespace PHPoAuthImpl\Service;

use PHPoAuthImpl\Di\Factory;
use OAuth\Common\Http\Uri\UriFactory;
use OAuth\Common\Storage\Session;
use OAuth\ServiceFactory;
use OAuth\Common\Consumer\Credentials;
use OAuth\OAuth1\Service\ServiceInterface as ServiceInterfaceV1;
use OAuth\OAuth2\Service\ServiceInterface as ServiceInterfaceV2;

class Collection
{
    /**
     * @var \OAuth\Common\Http\Uri\Uri
     */
    private $uri;

    /**
     * @var \OAuth\Common\Storage\TokenStorageInterface
     */
    private $storage;

    /**
     * @var OAuth\ServiceFactory
     */
    private $serviceFactory;

    private $factory;

    /**
     * @var array
     */
    private $services = [];

    public function __construct()
    {
        $uriFactory = new UriFactory();

        $this->uri = $uriFactory->createFromSuperGlobalArray($_SERVER);
        $this->uri->setQuery('');

        $this->storage = new Session();

        $this->serviceFactory = new ServiceFactory();

        $this->factory = new Factory;
    }

    public function add($name, $key, $secret)
    {
        $this->services[$this->normalizeName($name)] = $this->serviceFactory->createService(
            $name,
            new Credentials($key, $secret, $this->uri->getAbsoluteUri()),
            $this->storage
        );

        $this->factory->addService($this->services[$this->normalizeName($name)]);

        return $this;
    }

    public function request($path, array $params = [])
    {
        $parts = [];
        foreach ($path as $item) {
            $parts[] = $item;
        }

        $name   = $this->normalizeName(array_shift($parts));
        $method = array_pop($parts);

        $parts = array_map('strtolower', $parts);
        $parts = array_map('ucfirst', $parts);

        $abstractedServiceName = '\\PHPoAuthImpl\\Service\\' . $name . '\\' . implode('\\', $parts);

        $service = $this->factory->build($abstractedServiceName);

        //$service = new $abstractedServiceName($this->services[$name]);

        return $service->$method();
    }

    public function isAuthenticated($name)
    {
        return $this->storage->hasAccessToken($this->normalizeName($name));
    }

    public function authorize($name)
    {
        $name = $this->normalizeName($name);

        if ($this->services[$name] instanceof ServiceInterfaceV1) {
            $token = $this->services[$name]->requestRequestToken();

            $url = $this->services[$name]->getAuthorizationUri(array(
                'oauth_token' => $token->getRequestToken(),
            ));
        }

        header('Location: ' . $url);
        exit;
    }

    public function getAccessToken($name, $token, $verifier)
    {
        $name = $this->normalizeName($name);

        $token = $this->storage->retrieveAccessToken($name);

        $this->services[$name]->requestAccessToken(
            $token,
            $verifier,
            $token->getRequestTokenSecret()
        );
    }

    private function normalizeName($name)
    {
        return ucfirst(strtolower($name));
    }
}
