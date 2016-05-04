<?hh

namespace Pi\ServiceModel;

enum AuthGrantType : string {
	AuthorizationCode = 'authorization_code';
	ClientCredentials = 'client_credentials';
	Password = 'refresh_token';
	RefreshToken = 'refresh_token';
}