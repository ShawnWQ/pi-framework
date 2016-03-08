<?hh
namespace Pi\IdentitiServices\Interfaces;
interface IUserAddressAttributess {

	public function getFormatted();

	public function getStreetAddress();

	public function getLocality();

	public function getRegion();

	public function getPostalCode();

	public function getCountry();
}