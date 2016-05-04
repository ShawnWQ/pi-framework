<?hh
namespace Pi\IdentitiServices\Interfaces;
interface IUserSingleAttributes {

	/**
	 * @description
	 * Unique identifier for the User, typically used by the user to directly authenticate to the service provider. 
	 * Often displayed to the user as their unique identifier within the system (as opposed to id or externalId, which are generally opaque and not user-friendly identifiers). 
	 * Each User MUST include a non-empty userName value. 
	 * This identifier MUST be unique across the Service Consumer's entire set of Users. REQUIRED.
	 */
	public function getUserMame();

	public function getUserNameAttributes();

	/**
	 * @description
	 * The name of the User, suitable for display to end-users. 
	 * Each User returned MAY include a non-empty displayName value. 
	 * The name SHOULD be the full name of the User being described if known (e.g. Babs Jensen or Ms. Barbara J Jensen, III), but MAY be a username or handle, if that is all that is available (e.g. bjensen).
	 * The value provided SHOULD be the primary textual label by which this User is normally displayed by the Service Provider when presenting it to end-users.
	 */
	public function getDisplayName();

	/**
	 * @description
	 * The casual way to address the user in real life, e.g. "Bob" or "Bobby" instead of "Robert".
	 * This attribute SHOULD NOT be used to represent a User's username (e.g. bjensen or mpepperidge).
	 */
	public function getNickName();

	/**
	 * @description
	 * A fully qualified URL to a page representing the User's online profile.
	 */
	public function getProfileUrl();

	/**
	 * @description
	 * The user’s title, such as “Vice President.”
	 */
	public function getTitle();

	/**
	 * @description
	 * Used to identify the organization to user relationship. 
	 * Typical values used might be "Contractor", "Employee", "Intern", "Temp", "External", and "Unknown" but any value may be used.
	 */
	public function getType();

	public function getPreferredLanguage();

	public function getLocale();

	public function getTimezone();

	public function getActive();

	public function getPassword();
}