<?hh

namespace Pi\Auth\Interfaces;

use Pi\Interfaces\IMeta;

interface IUserAuth extends IUserAuthDetailsExtended, IMeta {

  public function getId();

  public function getPrimaryEmail() : string;

  public function getSalt() : string;

  public function getPasswordHash() : string;

  public function getDigestHalt() : string;

  public function getRoles() : arrat;

  public function getPermissions() : array;

  public function getRefId() : string;

  public function getInvalidLoginsAttempt() : int;

  public function getLastLoginAttempt() : ?\DateTime;

  public function getLockedDate() : ?\DateTime;

  public function getCreatedDate() : \DateTime;

  public function getModifiedDate() : \DateTime;
}
