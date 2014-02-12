<?php

namespace PHPoAuthImpl\Service;

use OAuth\Common\Http\Uri\UriFactory;
use OAuth\Common\Storage\Session;
use OAuth\ServiceFactory;
use OAuth\Common\Consumer\Credentials;

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
    }

    public function add($name, $key, $secret)
    {
        $this->services[$name] = $this->serviceFactory->createService(
            $name,
            new Credentials($key, $secret, $this->uri->getAbsoluteUri()),
            $this->storage
        );

        return $this;
    }

    public function isAuthenticated($name)
    {
        return $this->storage->hasAccessToken($name);
    }
}
