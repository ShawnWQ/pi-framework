<?hh

namespace Pi;

/**
 * Contains the standard set of headers applicable to an HTTP request.
 */
enum HttpRequestHeaders: string {
	/**
	 * Content-Types that are acceptable
	 */
	Accept = 'Accept';
	/**
	 * Character sets that are acceptable
	 */
	AcceptCharset = 'Accept-Charset';
	/**
	 * Acceptable encodings. See HTTP compression.
	 */
	AcceptEncoding = 'Accept-Encoding';
	/**
	 * Acceptable languages for response
	 */
	AcceptLanguage = 'Accept-Language';
	/**
	 * Acceptable version in time
	 */
	AcceptDatetime = 'Accept-Datetime';
	/**
	 * Authentication credentials for HTTP authentication
	 */
    Authorization = 'Authorization';
    /**
     * Used to specify directives that MUST be obeyed by all caching mechanisms along the request/response chain
     */
    CacheControl = 'Cache-Control';
    /**
     * What type of connection the user-agent would prefer
     */
    Connection = 'Connection';
    /**
     * An HTTP cookie previously sent by the server with Set-Cookie (below
     */
    Cookie = 'Cookie';
    /**
     * The length of the request body in octets (8-bit bytes)
     */
    ContentLength = 'Content-Length';
    /**
     * A Base64-encoded binary MD5 sum of the content of the request body
     */
    ContentMD5 = 'Content-MD5';
    /**
     * The MIME type of the body of the request (used with POST and PUT requests)
     */
    ContentType = 'ContentType';
    /**
     * The date and time that the message was sent
     */
    Date = 'Date';
    /**
     * Indicates that particular server behaviors are required by the client
     */
    Except = 'Except';
    /**
     * The email address of the user making the request
     */
    From = 'From';
    /**
     * The domain name of the server (for virtual hosting), mandatory since HTTP/1.1. Although domain name are specified as case-insensitive[5][6], it is not specified whether the contents of the Host field should be interpreted in a case-insensitive manner[7] and in practice some implementations of virtual hosting interpret the contents of the Host field in a case-sensitive manner.[citation needed]
     */
    Host = 'Host';
    /**
     * Only perform the action if the client supplied entity matches the same entity on the server. This is mainly for methods like PUT to only update a resource if it has not been modified since the user last updated it.
     */
    IfMatch = 'If-Match';
    /**
     * Allows a 304 Not Modified to be returned if content is unchanged
     */
    IfModifiedSince = 'If-Modified-Since';
    /**
     * Allows a 304 Not Modified to be returned if content is unchanged, see HTTP ETag
     */
    IfNonceMatch = 'If-Nonce-Match';
    /**
     * If the entity is unchanged, send me the part(s) that I am missing; otherwise, send me the entire new entity
     */
    IfRange = 'If-Range';
    /**
     * Only send the response if the entity has not been modified since a specific time.
     */
    IfUnmodifiedSince = 'If-Unmodified-Since';
    /**
     * Limit the number of times the message can be forwarded through proxies or gateways.
     */
    MaxForwards = 'Max-Forwards';
    /**
     * Implementation-specific headers that may have various effects anywhere along the request-response chain.
     */
    Pragma = 'Pragma';
    /**
     * Authorization credentials for connecting to a proxy.
     */
    ProxyAuthorization = 'Proxy-Authorization';
    /**
     * Request only part of an entity. Bytes are numbered from 0.
     */
    Range = 'Range';
    /**
     * This is the address of the previous web page from which a link to the currently requested page was followed. (The word “referrer” is misspelled in the RFC as well as in most implementations.)
     */
    Referersic = 'Referer[sic]';
    /**
     * The transfer encodings the user agent is willing to accept: the same values as for the response header Transfer-Encoding can be used, plus the trailers value (related to the chunked transfer method) to notify the server it expects to receive additional headers (the trailers) after the last, zero-sized, chunk.
     */
    TE = 'TE';
    /**
     * Ask the server to upgrade to another protocol.
     */
    Upgrade = 'Upgrade';
    /**
     * The user agent string of the user agent
     */
    UserAgent = 'User-Agent';
    /**
     * Informs the server of proxies through which the request was sent.
     */
    Via = 'Via';
    /**
     * A general warning about possible problems with the entity body.
     */
    Warning = 'Warning';
}