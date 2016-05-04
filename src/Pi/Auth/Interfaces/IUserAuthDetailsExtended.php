<?hh

namespace Pi\Auth\Interfaces;

interface IUserAuthDetailsExtended {

  public function getUserName() : string;

  public function getDisplayName() : string;

  public function getFirstName() : string;

  public function getLastName() : string;

  public function getCompany() : string;

  public function getEmail() : string;

  public function getPhoneNumber() : string;

  public function getBirthDate() : ?\DateTime;

  public function getAddress() : string;

  public function getAddress2() : string;

  public function getCity() : string;

  public function getState() : string;

  public function getCountry() : string;

  public function getCulture() : string;

  public function getFullName() : string;

  public function getGender() : string;

  public function getLanguage() : string;

  public function getMailAddress() : string;

  public function getNickName() : string;

  public function getPostalCode() : string;

  public function getTimeZone() : string;
}
