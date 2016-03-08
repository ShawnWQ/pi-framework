<?hh

namespace Pi\Interfaces;

/**
 * @description
 * Perform all required authentication and remove these concerns from protected applications or services. Ie:
 * - Its a delegated auth provider
 * - It intercepts all requests that are made to the protected application and then forwards these requests through with appropriate authentication details.
 *
 * Validate tokens and exchange identity information such as aliasing of user accounts
 * Provides BASIC-AUTH authentication for cookie based authentication.
 * After authenticating a user it passes the users credentials to the protected applications by encoding the details (ie: in the HTTP header)
 *
 * Should be deployed as a load balanced cluster for avaibility
 */
interface IAccessService {

}


/**

It is implemented as an Apache Mod and interfaces with the Crowd’s API to validate tokens
and exchange identity information such as aliasing of user accounts.
As well as cookie based authentication it provides BASIC-AUTH authentication and elevated auth
capabilities (a ‘sudo’ equivalent for the web). After authenticating a user it passes the users
credentials through to the protected application (3) by encoding the details in the HTTP header.

This component is deployed as a load balanced cluster for availability.
*/
