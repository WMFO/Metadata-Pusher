<?php

require "auth.php";

define("LASTFM_API_URL", "http://ws.audioscrobbler.com/2.0/?format=json");
define("LASTFM_API_URL_SECURE", "https://ws.audioscrobbler.com/2.0/?format=json");

/*
 * Returns an associative array of the values in Last.fm's response
 * Documentation: http://www.last.fm/api/show/auth.getMobileSession
 */
function LastFM_getLastFMSession($username, $password){
    $APISignature = md5("api_key" . LASTFM_API_KEY . 
                        "method" . "auth.getMobileSession" . 
                        "password" . $password . 
                        "username" . $username . 
                         LASTFM_API_SECRET);

    $sessionRequest = new HttpRequest(LASTFM_API_URL_SECURE, HttpRequest::METH_POST);
    $sessionRequest->addPostFields(array("username" => $username,
                                         "password" => $password,
                                         "method" => "auth.getMobileSession",
                                         "api_key" => LASTFM_API_KEY,
                                         "api_sig" => $APISignature));
    try {
        $sessionRequest->send();
        if ($sessionRequest->getResponseCode() == 200) {
            return json_decode($sessionRequest->getResponseBody(), true);
        } else {
            return null;
        }
    } catch (HttpException $e) {
        echo $e;
    }
}

/*
 * Returns an associative array of the values in Last.fm's response
 * Documentation: http://www.last.fm/api/show/track.scrobble
 */
function LastFM_scrobbleSong($artist, $song, $album, $timestamp, $sessionKey){
    $APISignature = md5("api_key" . LASTFM_API_KEY . 
                        "artist" . $artist .
                        "method" . "track.scrobble" . 
                        "sk" . $sessionKey .
                        "timestamp" . $timestamp .
                        "track" . $song .
                        LASTFM_API_SECRET);
    
    $scrobbleRequest = new HttpRequest(LASTFM_API_URL_SECURE, HttpRequest::METH_POST);
    $scrobbleRequest->addPostFields(array("api_key" => LASTFM_API_KEY,
                                          "artist" => $artist,
                                          "method" => "track.scrobble",
                                          "sk" => $sessionKey,
                                          "timestamp" => $timestamp,
                                          "track" => $song,
                                          "api_sig" => $APISignature));
    try {
        $scrobbleRequest->send();
        if ($scrobbleRequest->getResponseCode() == 200) {
            return json_decode($scrobbleRequest->getResponseBody(), true);
        } else {
            return null;
        }
    } catch (HttpException $e) {
        echo $e;
    }
}

/*
 * Authorizes itself with Last.fm and scrobbles a song
 */
function LastFM_authenticateAndScrobble($artist, $song, $album, $timestamp){
    $userSession = getLastFMSession(WMFO_LASTFM_USERNAME, WMFO_LASTFM_PASSWORD);
    if ($userSession == null){
        echo "Error: Unexpected response from server when trying to authenticate";
        return 1;
    } elseif (isset($userSession['error'])){
        echo "Error " . $userSession['error'] . ": " . $userSession['message'];
       return $userSession['error'];
    } else {
        $sessionKey = $userSession['session']['key'];
        $response = scrobbleSong($artist, $track, $album, $timestamp, $sessionKey);
        
        if (isset($response['error'])){
            echo "Error " . $response['error'] . ": " . $response['message'];
            return $response['error'];
        }
    }
    return 0;
}

?>
