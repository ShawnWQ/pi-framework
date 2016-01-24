<?hh

namespace Collaboration\ServiceModel;



class PublishMeeting {
	
	/**
	 * The meeting id
	 */
	protected \MongoId $id;

	/**
	 * If not null, will be sent in emails, notifications
	 */
	protected ?string $observation;
}