<?hh

namespace Pi\Auth\Interfaces;

use Pi\Interfaces\IMeta;

interface IUserAuthDetails extends IAuthTokens, IMeta {

  public function getId();

  public function getUserId();

  public function getCreatedDate() : \DateTime;

  public function getModifiedDate() : \DateTime;

  public function getRefId() : ?int;

  public function getRefIdStr() : string;
}
