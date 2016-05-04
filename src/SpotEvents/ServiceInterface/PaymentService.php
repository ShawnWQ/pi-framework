<?hh

namespace SpotEvents\ServiceInterface;

use Pi\Common\ClassUtils;
use Pi\Service;
use Pi\EventSubscriber;
use SpotEvents\ServiceInterface\Interfaces\IPaymentProvider;
use SpotEvents\ServiceInterface\Data\PaymentRepository;
use SpotEvents\ServiceModel\CreatePaymentRequest;
use SpotEvents\ServiceModel\CreatePaymentResponse;
use SpotEvents\ServiceModel\GetPaymentRequest;
use SpotEvents\ServiceModel\GetPaymentResponse;
use SpotEvents\ServiceModel\Types\PaymentDto;
use SpotEvents\ServiceModel\Types\PaymentStatus;
use SpotEvents\ServiceModel\Types\PaymentEntity;
use SpotEvents\ServiceModel\ReceivePaymentRequest;
use SpotEvents\ServiceModel\PaymentReceiveRequest;
use SpotEvents\ServiceModel\PaymentReceiveResponse;
use SpotEvents\ServiceModel\FindPaymentRequest;
use SpotEvents\ServiceModel\FindPaymentResponse;

class PaymentService extends Service {
	
	public IPaymentProvider $provider;

    public PaymentRepository $paymentRepo;

    <<Request,Auth>>
	public function createPayment(CreatePaymentRequest $request)
	{
		$reference = $this->provider->createRef(12345, $request->getSubEntity(), rand(0, 9999), $request->getAmount());
        $entity = new PaymentEntity();
        $author = $this->request()->author();
        ClassUtils::mapDto($request, $entity);
        $entity->setAuthor($author);
        $entity->setReference($reference);
        $entity->setStatus(PaymentStatus::Created);
        if(is_null($request->getTitle())){
            $request->setTitle('Volupio - Pagamento Gerado Automaticamente');
        }
        
        $entity->setTitle($request->getTitle());
        $this->paymentRepo->insert($entity);

        $dto = new PaymentDto();
        ClassUtils::mapDto($entity, $dto);
        $response = new CreatePaymentResponse();
        $response->setPayment($dto);

        return $response;
	}

    <<Request,Method('GET'),Route('/payment/report/:id')>>
    public function getPayment(GetPaymentRequest $request)
    {
        if(!is_null(($request->getReference()))) {
            $entity = $this->paymentRepo->getByReference($request->getReference());
        } else {
            $entity = $this->paymentRepo->get($request->getId());    
        }
        $dto = new PaymentDto();
        ClassUtils::mapDto($entity, $dto);

        $response = new GetPaymentResponse();
        $response->setPaymentDto($dto);

        return $response;
    }

    <<Request,Route('/payment/report'),Method('GET')>>
    public function findPayments(FindPaymentRequest $request)
    {
        
        $response = new FindPaymentResponse();
        $data = $this->paymentRepo
            ->queryBuilder('SpotEvents\ServiceModel\Types\PaymentDto')
            ->hydrate()
            ->find()
            ->getQuery()
            ->execute();

        $response->setPayments($data);

        return $response;
    }

    public function receivePaymentIfThen(PaymentReceiveRequest $request)
    {
        //die(print_r($request->getAccessToken()));
    }

    public function getEventsSubscribed()
    {
        return array('receivePaymentIfThen');
    }


	<<Request,Route('/ifthen/payment'),Method('GET')>>
	public function receivePayment(ReceivePaymentRequest $request)
	{
        $req = new PaymentReceiveRequest();
        $req->setAccessToken('123123123');
        $this->eventManager()->dispatch('receivePaymentIfThen', $req);
        $this->paymentRepo->updateState($request->getReferencia(), PaymentStatus::PaymentReceived);
        $response = new PaymentReceiveResponse();

        return $response;
	}
}