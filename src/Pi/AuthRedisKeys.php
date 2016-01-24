<?hh

namespace Pi\Auth;

enum AuthRedisKeys : string {
	AuthToken = 'auth-token::{0}';
	Token = 'token::{0}';
}