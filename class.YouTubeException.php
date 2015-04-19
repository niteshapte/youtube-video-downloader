<?php
/**
 * Main YouTube exception class.
 * 
 * @package YouTube Video Downloader
 * @author Nitesh Apte
 * @copyright 2015
 * @version 1.0
 */
class YouTubeException extends Exception { }

/**
 * Empty List.
 *
 * @package YouTube Video Downloader
 * @author Nitesh Apte
 * @copyright 2015
 * @version 1.0
 */
class YouTubeEmptyListException extends YouTubeException { }

/**
 * Invalid video id.
 *
 * @package YouTube Video Downloader
 * @author Nitesh Apte
 * @copyright 2015
 * @version 1.0
 */
class YouTubeInvalidVideoIdException extends YouTubeException { }

/**
 * Curl extension not loaded.
 *
 * @package YouTube Video Downloader
 * @author Nitesh Apte
 * @copyright 2015
 * @version 1.0
 */
class YouTubeCurlNotAvailableException extends YouTubeException { }

/**
 * Unsupported download method.
 *
 * @package YouTube Video Downloader
 * @author Nitesh Apte
 * @copyright 2015
 * @version 1.0
 */
class YouTubeUnsupportedDownloadMethodException extends YouTubeException { }

/**
 * Unsupported video format.
 *
 * @package YouTube Video Downloader
 * @author Nitesh Apte
 * @copyright 2015
 * @version 1.0
 */
class YouTubeUnsupportedVideoFormatException extends YouTubeException { }

/**
 * No download location.
 *
 * @package YouTube Video Downloader
 * @author Nitesh Apte
 * @copyright 2015
 * @version 1.0
 */
class YouTubeEmptyDownloadDestinationException extends YouTubeException { }

/**
 * Video not found.
 *
 * @package YouTube Video Downloader
 * @author Nitesh Apte
 * @copyright 2015
 * @version 1.0
 */
class YouTubeVideoNotFoundException extends YouTubeException { }

/**
 * Video not available in given format.
 *
 * @package YouTube Video Downloader
 * @author Nitesh Apte
 * @copyright 2015
 * @version 1.0
 */
class YouTubeVideoNotAvailableForGivenFormatException extends YouTubeException { }
?>
