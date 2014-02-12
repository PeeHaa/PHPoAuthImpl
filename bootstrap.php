<?php

namespace PHPoAuthImpl;

/**
 * Bootstrap the lib
 */
require_once __DIR__ . '/src/PHPoAuthImpl/bootstrap.php';

exit;

/**
 * Create a new instance of the URI class with the current URI, stripping the query string
 */
$uriFactory = new UriFactory();
$currentUri = $uriFactory->createFromSuperGlobalArray($_SERVER);
$currentUri->setQuery('');

/**
 * Load the credentials
 */
require_once __DIR__ . '/credentials.php';

/**
 * Setup the token storage
 */
$storage = new Session();

/**
 * Setup the service factory
 */
$serviceFactory = new ServiceFactory();

/**
 * Setup the credentials for the requests
 */
$credentials = new Credentials(
    $credentials['twitter']['key'],
    $credentials['twitter']['secret'],
    $currentUri->getAbsoluteUri()
);

// Instantiate the twitter service using the credentials, http client and storage mechanism for the token
/** @var $twitterService Twitter */
$twitterService = $serviceFactory->createService('twitter', $credentials, $storage);

if ($storage->hasAccessToken('Twitter')) {
    $twitter = new \PHPoAuthImpl\Service\Twitter\Help($twitterService);

    echo '<h1>help/tos</h1>';

    echo '<pre>';
    var_dump($twitter->getTos());
    echo '</pre>';
} elseif (!empty($_GET['oauth_token'])) {
    $token = $storage->retrieveAccessToken('Twitter');

    // This was a callback request from twitter, get the token
    $twitterService->requestAccessToken(
        $_GET['oauth_token'],
        $_GET['oauth_verifier'],
        $token->getRequestTokenSecret()
    );

    $twitter = new \PHPoAuthImpl\Service\Twitter\Help($twitterService);

    echo '<h1>help/configuration</h1>';

    echo '<pre>';
    var_dump($twitter->getConfiguration());
    echo '</pre>';
/*
    echo '<h1>help/languages</h1>';

    echo '<pre>';
    var_dump($twitter->getLanguages());
    echo '</pre>';

    echo '<h1>help/privacy</h1>';

    echo '<pre>';
    var_dump($twitter->getPrivacy());
    echo '</pre>';

    echo '<h1>help/tos</h1>';

    echo '<pre>';
    var_dump($twitter->getTos());
    echo '</pre>';

    echo '<h1>help/rate-limit-status</h1>';

    echo '<pre>';
    var_dump($twitter->getRateLimitStatus());
    echo '</pre>';
*/
    // Send a request now that we have access token
    //$result = json_decode($twitterService->request('account/verify_credentials.json'));

    //echo 'result: <pre>' . print_r($result, true) . '</pre>';

} elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
    // extra request needed for oauth1 to request a request token :-)
    $token = $twitterService->requestRequestToken();

    $url = $twitterService->getAuthorizationUri(array('oauth_token' => $token->getRequestToken()));
    header('Location: ' . $url);
} else {
    $url = $currentUri->getRelativeUri() . '?go=go';
    echo "<a href='$url'>Login with Twitter!</a>";
}
