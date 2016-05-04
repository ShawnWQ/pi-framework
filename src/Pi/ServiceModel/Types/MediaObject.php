<?hh

namespace Pi\ServiceModel\Types;

/**
 * An image, video, or audio object embedded in a web page. 
 * Note that a creative work may have many media objects associated with it on the same web page.
 * For example, a page about a single song (MusicRecording) may have a music video (VideoObject), and a high and low bandwidth audio stream (2 AudioObject's).
 */
class MediaObject extends CreativeWork {

	/**
	 * The bitrate of the media object.
	 */
	protected string $bitrate;

	/**
	 * File size in (mega/kilo) bytes.
	 */
	protected string $contentSize;

	/**
	 * Actual bytes of the media object, for example the image file or video file.
	 */
	protected string $contentUrl;

	/**
	 * The duration of the item (movie, audio recording, event, etc.) in ISO 8601 date format.
	 */
	protected string $duration;

	/**
	 * A URL pointing to a player for a specific video. 
	 * In general, this is the information in the src element of an embed tag and should not be the same as the content of the loc tag.
	 */
	protected string $embedUrl;

	/**
	 * Example mp3, mpeg4, etc.
	 */
	protected string $encodingFormat;

	/**
	 * Date the content expires and is no longer useful or available. Useful for videos.
	 */
	protected \DateTime $expires;

	protected string $height;

	protected string $width;

	protected \DateTime $uploadedDate;

	protected bool $requiresSubscription;

	protected array $regionsAllowed;

	protected string $playerType;
}