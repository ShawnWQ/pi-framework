<?hh

namespace Pi\ServiceModel\Types;

class Review extends CreativeWork {

	protected Thing $itemReviewed;

	protected string $reviewBody;

	protected Rating $reviewRating;
}