<?php

namespace PHPoAuthImpl\Service\Twitter;

use OAuth\OAuth1\Service\Twitter;

class Help
{
    private $service;

    public function __construct(Twitter $service)
    {
        $this->service = $service;
    }

    /**
     * https://dev.twitter.com/docs/api/1.1/get/help/configuration
     */
    public function getConfiguration()
    {
        return json_decode($this->service->request('help/configuration.json'));
    }

    /**
     * https://dev.twitter.com/docs/api/1.1/get/help/languages
     */
    public function getLanguages()
    {
        return json_decode($this->service->request('help/languages.json'));
    }

    /**
     * https://dev.twitter.com/docs/api/1.1/get/help/privacy
     */
    public function getPrivacy()
    {
        return json_decode($this->service->request('help/privacy.json'));
    }

    /**
     * https://dev.twitter.com/docs/api/1.1/get/help/tos
     */
    public function getTos()
    {
        return json_decode($this->service->request('help/tos.json'));
    }

    /**
     * https://dev.twitter.com/docs/api/1.1/get/application/rate_limit_status
     */
    public function getRateLimitStatus(array $resources = [])
    {
        $queryString = '';

        if (!empty($resources)) {
            $queryString = '?resources=' . implode(',', $resources);
        }

        return json_decode($this->service->request('help/rate_limit_status.json' . $queryString));
    }
}
