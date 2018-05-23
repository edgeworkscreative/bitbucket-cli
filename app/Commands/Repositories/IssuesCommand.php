<?php

namespace App\Commands\Repositories;

use App\Commands\BitbucketCommand;
use App\Providers\BitbucketConfigProvider;
use Bitbucket\API\Repositories\Issues;

abstract class IssuesCommand extends BitbucketCommand
{
    protected function create()
    {
        $issue = new Issues;
	    $issue->getClient()->addListener($this->createAuthListener());

        return $issue;
    }

    protected function createAuthListener()
    {
        $bitbucket_config = new BitbucketConfigProvider;

        if ($bitbucket_config->hasOAuth2()) {
            return new \Bitbucket\API\Http\Listener\OAuth2Listener([
                'client_id' => $bitbucket_config->getOAuth2Id(),
                'client_secret' => $bitbucket_config->getOAuth2Secret(),
            ]);
        } elseif ($bitbucket_config->hasBasicAuth()) {
            return new \Bitbucket\API\Http\Listener\BasicAuthListener($bitbucket_config->getBasicAuthUsername(), $bitbucket_config->getBasicAuthPassword());
        }
    }
}
