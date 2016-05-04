<?hh

namespace Pi\ServiceModel\Types;

class Rating extends Thing {
	
	/**
	 * The highest value allowed in this rating system. If bestRating is omitted, 5 is assumed.
	 */
	protected int $bestRating;

	/**
	 * The rating for the content.
	 */
	protected string $ratingValue;

	/**
	 * The lowest value allowed in this rating system. If worstRating is omitted, 1 is assumed.
	 */
	protected int $worstRating;
}