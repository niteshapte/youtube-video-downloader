 <?php
/**
 * Download videos from YouTube using WGET or cURL
 *
 * @package YouTubeVideoDownloader
 * @author Nitesh Apte
 * @copyright 2015
 * @version 1
 * @license GPL v3
 */

class YouTubeVideoDownloader {
   
    private $videoID;
   
    private $supportedVideoFormat = array("5", "18", "34");
   
    private $downloadUrl;
   
    private $videoFormat;
   
    private $DESTINATION = "/root/Videos/";
   
    private $title;
   
    public function __construct($videoID, $videoFormat) {
        $this->videoID = $videoID;
        if(!in_array($videoFormat, $this->supportedVideoFormat)) {
            $videoFormat = "5";
        }
        $this->videoFormat = $videoFormat;
        $this->createDownloadLink();
    }
   
    public function createDownloadLink() {
        $infoPage = file_get_contents("http://youtube.com/get_video_info?video_id=".$this->videoID);
        parse_str($infoPage, $arr);
       
        $this->title = $arr['title'];
        $urlData = $arr['url_encoded_fmt_stream_map'];
        $dataSet = explode(',', $urlData);
        parse_str(urldecode($dataSet[0]), $data);
        $url = $data['url'];
        $sig = $data['signature'];
        unset($data['type']);
        unset($data['url']);
        unset($data['sig']);
        $this->downloadUrl = str_replace('%2C', ',' ,$url.'&'.http_build_query($data).'&signature='.$sig.'&fmt='.$this->videoFormat);
    }
   
    public function wgetDownload() {
        $code = 'wget --output-document='.$this->DESTINATION.str_replace(" ", "-", $this->title).'.mp4 '."'$this->downloadUrl'";
        shell_exec($code);
    }
   
    public function curlDownload() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->downloadUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSLVERSION,3);
        $data = curl_exec ($ch);
        $error = curl_error($ch);
        curl_close ($ch);
       
        $destination = $this->DESTINATION.$this->title.'.mp4';
        $file = fopen($destination, "w+");
        fputs($file, $data);
        fclose($file);
    }
}
?> 
