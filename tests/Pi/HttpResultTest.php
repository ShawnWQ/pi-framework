<?php
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 5/13/15
 * Time: 6:57 PM
 */

use Pi\HttpResult;
use Pi\HttpStatusCode;
use Pi\HttpHeaders;

class HttpResultTest extends \PHPUnit_Framework_TestCase{

    public function testCreateAnRedirectResultAndSetProperlyTheHeaders()
    {
        $redirect = HttpResult::redirect('/new-uri');
        $this->assertTrue($redirect instanceof HttpResult);
        $this->assertTrue($redirect->getHeaders()->contains(HttpHeaders::Location));
        $this->assertEquals($redirect->status(), HttpStatusCode::Found);
    }

    public function testCreateCustomERror()
    {
    	$response = HttpResult::createCustomError('code', 'message');
    	$this->assertTrue($response instanceof HttpResult);
    	$this->assertTrue($response->response()['errorCode'] === 'code');

    }
}