# Auth Plugin

The authentication plugin provides several providers for users to authenticate agains Pi application.

There're two scenarious for users authenticating with Auth features:

- User register a account with RegisterService: user and use Credentials Provider
- User authenticate with AuthProvider for the first time, a new account is created

@TODO

Then authenticating with AuthProvider (besides credentials), if the user is already registered should create a UserAuthDetails

IAuthSession session presented in each request. The session is fetched with user authentication information. Each session may have many IAuthTokens

IAuthTokens available oauth tokens information for the current user session

IUserAuth represents an user entity.

IUserAuthDetails represents the user oauth information related to a provider

IAuthTokens is fetched in IAuthSession with oauth providers authentication information

## AuthService

## Register Service

Register a new account with information submited by the user.

Provides authentication with Credentials Provider

## Auth Provider


### Credentials Provider

The basic authentication with username/email and password.

To authenticate with credentials, the user must register a new account with RegisterService