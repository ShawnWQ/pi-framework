<?hh
namespace Pi\IdentitiServices\Interfaces;
interface IUserMultiValuedAttributes {
	/**
	 * @description
	 * E-mail addresses for the User. 
	 * The value SHOULD be canonicalized by the Service Provider, e.g. bjensen@example.com instead of bjensen@EXAMPLE.COM. Canonical Type values of work, home, and other.
	 */
	public function getEmails();

	public function getPhoneNumbers();

	/**
	 * @description
	 * Instant messaging address for the User
	 */
	public function getIms();

	public function getPhotos();

	public function getAddresses();

	public function getGroups();

	public function getEntitlements();

	public function getRoles();

	public function getX509Certificates();

}