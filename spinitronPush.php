<?php

//Include credentials
require_once('auth.php');

//Get (and sanitize at some point?) Spinitron push data
$artist = $_POST['artist'];
$song = $_POST['song'];
$album = $_POST['album'];
$timestamp = $_POST['time'];

//Scrobble to Last.FM
require_once('LastFM.php');
if (!LastFM_authenticateAndScrobble($artist, $song, $album, $timestamp)){
    "Error: Could not scobble to Last.FM";
}

?>
