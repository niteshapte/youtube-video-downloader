<?php
include_once 'class.YouTubeException.php';

/**
 * Set configuration for download
 * 
 * @package YouTube Video Downloader
 * @author Nitesh Apte
 * @copyright 2015
 * @version 1.1
 * @license GPL v3
 */
class YouTubeBean {
	
	private $videoId;
	
	private $supportedVideoFormat = array("5", "17", "18", "22", "36", "43"); // this is sequence of itag/qaulity of video in url
	
	private $videoFormat;
	
	private $supportedMethods = array("wget", "curl");
	
	private $method;
	
	private $destination;
	
	/**
	 * Set YouTube video id.
	 * 
	 * @param String $YouTubeVideoID
	 * @throws YouTubeInvalidVideoIdException
	 */
	public function setVideoId($YouTubeVideoID) {
		if(strlen($YouTubeVideoID) != 11):
			throw new YouTubeInvalidVideoIdException("Invalid YouTube video identifier '{$YouTubeVideoID}'. Make sure it is of 11 characters.");
		endif;
		$this->videoId = $YouTubeVideoID;
	}
	
	/**
	 * Retrieve YouTube video id.
	 * 
	 * @return $this->videoId
	 */
	public function getVideoId() {
		return $this->videoId;
	}
	
	/**
	 * Set the video format of video.
	 * 
	 * @param String $YouTubevideoFormat
	 * @throws YouTubeUnsupportedVideoFormatException
	 */
	public function setVideoFormat($YouTubevideoFormat) {
		if(!in_array($YouTubevideoFormat, $this->supportedVideoFormat)):
			throw new YouTubeUnsupportedVideoFormatException("Video format '{$YouTubevideoFormat}' is not supported. Current supported formats are - 5, 17, 18, 22, 36, 43. \n");
		endif;
		$this->videoFormat = $YouTubevideoFormat;		
	}
	
	/**
	 * Retrieve the video format.
	 * 
	 * @return $this->videoFormat
	 */
	public function getVideoFormat() {
		return $this->videoFormat;
	}
	
	/**
	 * Set the download method.
	 * 
	 * @param String $downloadMethod
	 * @throws YouTubeUnsupportedDownloadMethodException
	 * @throws YouTubeCurlNotAvailableException
	 */
	public function setMethod($downloadMethod) {
		if(!in_array($downloadMethod, $this->supportedMethods)):
			throw new YouTubeUnsupportedDownloadMethodException("Unknown download method '{$downloadMethod}'. Only download via WGET and cURL is supported.");
		endif;		
		if($downloadMethod == "curl" && !in_array('curl', get_loaded_extensions())):
			throw new YouTubeCurlNotAvailableException("cURL is not enabled. Please enable it in php.ini file of your server and then restart the server for changes to take effect. Else try WGET method. \n");
		endif;		
		$this->method = $downloadMethod;		
	}
	
	/**
	 * Retrieve the download method.
	 * 
	 * @return $this->method
	 */
	public function getMethod() {
		return $this->method;
	}
	
	/**
	 * Set the download location.
	 * 
	 * @param String $downloadPath
	 * @throws YouTubeEmptyDownloadDestinationException
	 */
	public function setDestination($downloadPath) {
		if($downloadPath == ""):
			throw new YouTubeEmptyDownloadDestinationException("Download location is empty.");
		endif;
		$this->destination = $downloadPath;
	}
	
	/**
	 * Retrieve the download location.
	 * 
	 * @return $this->destination
	 */
	public function getDestination() {
		return $this->destination;
	}
}
?>
