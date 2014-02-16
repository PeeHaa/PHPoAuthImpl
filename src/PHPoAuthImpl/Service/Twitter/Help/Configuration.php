<?php

namespace PHPoAuthImpl\Service\Twitter\Help;

use PHPoAuthImpl\Service\Twitter\Help;

class Configuration
{
    private $endpoint;

    public function __construct(Help $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function getCharactersPreservedPerMedia()
    {
        $result = $this->endpoint->getConfiguration();

        return $result->characters_reserved_per_media;
    }

    public function getMaxMediaPerUpload()
    {
        $result = $this->endpoint->getConfiguration();

        return $result->max_media_per_upload;
    }

    public function getNonUsernamePaths()
    {
        $result = $this->endpoint->getConfiguration();

        return $result->non_username_paths;
    }
}
