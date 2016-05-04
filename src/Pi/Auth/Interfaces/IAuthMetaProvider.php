<?hh

namespace Pi\Auth\Interfaces;

interface IAuthMetaProvider {

  public function addMetadata(IAuthTokens $tokens, array $authInfo) : void;
  public function getProfileUrl(IAuthSession $authSession, ?string $defaultUrl = null) : string;
}
