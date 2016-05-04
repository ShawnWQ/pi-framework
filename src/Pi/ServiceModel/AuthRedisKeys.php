<?hh

namespace Pi\ServiceModel;

enum AuthRedisKeys : string {
	Token = "token::%s";
	AuthTokenReq = "token-req::%s";
}