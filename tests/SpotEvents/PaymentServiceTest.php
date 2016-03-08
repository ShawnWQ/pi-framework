<?php
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/9/15
 * Time: 4:43 AM
 */
use SpotEvents\ServiceModel\CreatePaymentResponse;
use Mocks\AuthMock;

class PaymentServiceTest extends PHPUnit_Framework_TestCase{

    protected $host;
    protected $paymentRepo;

    public function setUp()
    {
        $this->host = new \Mocks\SpotEventMockHost();
        $this->host->init();
        AuthMock::mock();
        $tmp = __DIR__ .'/../tmp';
        $this->host->config()->configsPath($tmp);
        $this->host->config()->cacheFolder($tmp);
        $this->paymentRepo = $this->host->tryResolve('SpotEvents\ServiceInterface\Data\PaymentRepository');
    }
    
    public function testCreatePayment()
    {
        $req = new \SpotEvents\ServiceModel\CreatePaymentRequest();

        $response = $this->host->execute($req, new \Mocks\HttpRequestMock($req));

        $this->assertTrue($response instanceof CreatePaymentResponse);

        $db = $this->paymentRepo->get($response->getPayment()->getId());
        $this->assertEquals($db->getReference(), $response->getPayment()->getReference());
    }
}