<?php
require_once 'errorhandler.php';
require 'class.YouTubeBean.php';
require 'class.YouTubeVideoDownloader.php';

$videoList = array();

if(PHP_SAPI === 'cli') {  // via command mode
	array_shift($argv);
	$videoList = $argv;
} else if(isset($_GET['video'])) {   // via browser
	$videos = @trim($_GET['video']);
	if(!empty($videos)):
		$videoList = explode(",", $videos);
	endif;		
} else { 
	$videoList = array();	// add video id manually
}

try {
	header('Content-Type: text/html; charset=utf-8');
	
	for($i = 0; $i < sizeof($videoList); $i++):
	
		$bean = new YouTubeBean();
		$bean->setVideoId($videoList[$i]);
		$bean->setVideoFormat("22");
		$bean->setMethod("curl");
		$bean->setDestination("/root/Videos/");	// Make sure this folder is writable
		
		$downloader = new YouTubeVideoDownloader();
		$downloader->startDownload($bean);
		
	endfor;
} catch (YouTubeInvalidVideoIdException $e) {
	die("<strong>YouTubeInvalidVideoIdException</strong> : ".$e->getMessage());
} catch (YouTubeUnsupportedVideoFormatException $e) {
	die("<strong>YouTubeUnsupportedVideoFormatException</strong> : ".$e->getMessage());
} catch (YouTubeUnsupportedDownloadMethodException $e) {
	die("<strong>YouTubeUnsupportedDownloadMethodException</strong> : ".$e->getMessage());
} catch (YouTubeCurlNotAvailableException $e) {
	die("<strong>YouTubeCurlNotAvailableException</strong> : ".$e->getMessage());
} catch (YouTubeEmptyDownloadDestinationException $e) {
	die("<strong>YouTubeEmptyDownloadDestinationException</strong> : ".$e->getMessage());
} catch (YouTubeVideoNotFoundException $e) {
	die("<strong>YouTubeVideoNotFoundException</strong> : ".$e->getMessage());
} catch(YouTubeVideoNotAvailableForGivenFormatException $e) {
	die("<strong>YouTubeVideoNotAvailableForGivenFormatException</strong> : ".$e->getMessage());
} catch (YouTubeException $e) {
	die("<strong>YouTubeException</strong> : Something went wrong. Message : ".$e->getMessage());
}
?>
