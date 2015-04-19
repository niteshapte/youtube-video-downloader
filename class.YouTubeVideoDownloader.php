<?php
include_once 'class.YouTubeException.php';
include_once 'class.YouTubeBean.php';

/**
 * Download videos from YouTube using WGET or cURL
 *
 * @package YouTubeVideoDownloader
 * @author Nitesh Apte
 * @copyright 2015
 * @version 1.1
 * @license GPL v3
 */
class YouTubeVideoDownloader {
	
	private $downloadLink;
	
	private $videoTitle;
	
	private $videoExtension;
	
	/**
	 * Download video using wget or curl
	 * 
	 * @param YouTubeBean $bean
	 */
	public function startDownload(YouTubeBean $bean) {
		$this->createDownloadLink($bean->getVideoId(), $bean->getVideoFormat());
		$bean->getMethod() == "curl" ? $this->curlDOwnload($bean->getDestination()) : $this->wgetDownload($bean->getDestination());	
	}
	
	/**
	 * Create the download link
	 * 
	 * @param string $videoId
	 * @param string $videoFormat
	 */
	private function createDownloadLink($videoId, $videoFormat) {
		$infoPage = $this->getVideoInfo($videoId);	// get the info page of video
		parse_str($infoPage, $arr);
		
		$this->videoTitle = ($arr['title']);	// set the title of the video
		
		$videoFormatLink = $this->getVideoFormatLink($arr['url_encoded_fmt_stream_map'], $videoFormat);	// get the link of given video format
		parse_str(urldecode($videoFormatLink), $data);
		
		$ext = explode("/", $data['mime']);
		$this->videoExtension = ".".$ext[1];
		$url = $data['url'];
		$sig = $data['signature'];
		unset($data['type']);
		unset($data['url']);
		unset($data['sig']);
		$this->downloadLink = str_replace('%2C', ',' ,$url.'&'.http_build_query($data).'&signature='.$sig);
	}
	
	/**
	 * Get the info page of the video
	 * 
	 * @param string $videoId
	 * @throws YouTubeVideoNotFoundException
	 * @return string
	 */
	private function getVideoInfo($videoId) {
		$infoPage = file_get_contents("http://youtube.com/get_video_info?video_id=".$videoId);	// get the info page of video
		if(strpos($infoPage, "errorcode=100") !== FALSE):
			throw new YouTubeVideoNotFoundException("The video for id '{$videoId}' doesn't exists on YouTube.");
		endif;
		return $infoPage;
	}
	
	/**
	 * Get download link of given format.
	 * 
	 * @param string $streamMap
	 * @param string $videoFormat
	 * @throws YouTubeVideoNotAvailableForGivenFormatException
	 * @return string
	 */
	private function getVideoFormatLink($streamMap, $videoFormat) {
		$dataSet = explode(',', $streamMap);
		$finalUrl = "";
		for($i = 0; $i < sizeof($dataSet); $i++):
			if(strpos($dataSet[$i], "itag=".$videoFormat) !== FALSE):
				$finalUrl = $dataSet[$i];
			endif;
		endfor;
		if($finalUrl == ""):		
			throw new YouTubeVideoNotAvailableForGivenFormatException("Video for format '{$videoFormat}' doesn't exist. Try with different formats. Available formats - 5, 17, 18, 22, 36, 43. \n");	
		endif;
		return $finalUrl;
	}
	
	/**
	 * Download using wget
	 * 
	 * @param string $destination
	 */
	private function wgetDownload($destination) {
		$title = str_replace(" ", "\ ", $this->videoTitle);
		$title = str_replace("(", "\(", $title);
		$title = str_replace(")", "\)", $title);
		$code = 'wget --output-document='.$destination.$title.$this->videoExtension.' '."'$this->downloadLink'";
		shell_exec($code);
		echo "\n Download complete using WGET \n";
	}
	
	/**
	 * Download using curl
	 * 
	 * @param string $destination
	 */
	private function curlDOwnload($destination) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->downloadLink);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSLVERSION,3);
		$data = curl_exec($ch);
		$error = curl_error($ch);
		curl_close ($ch);
		
		$path = $destination.$this->videoTitle.$this->videoExtension;
		$file = fopen($path, "w+");
		fputs($file, $data);
		fclose($file);
		
		echo "\n Download complete using cURL \n";
	}	
}
