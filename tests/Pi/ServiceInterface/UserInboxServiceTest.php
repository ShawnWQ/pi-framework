<?hh

use Pi\ServiceModel\GetUserInboxRequest;
use Pi\ServiceModel\GetUserInboxResponse;
use Pi\ServiceModel\SendMessageRequest;
use Pi\ServiceModel\SendMessageResponse;
use Pi\ServiceModel\Types\Message;
use Pi\ServiceModel\Types\InboxMessage;
use Pi\ServiceInterface\Data\UserInboxRepository;
use Mocks\MockHostProvider;
use Mocks\BibleHost;
use Mocks\AuthMock;

class UserInboxServiceTest extends \PHPUnit_Framework_TestCase {

	protected $host;

	protected $inboxRepo;

	public function setUp()
	{
		$this->host = new BibleHost();
		$this->inboxRepo = $this->host->tryResolve('Pi\ServiceInterface\Data\UserInboxRepository');
	}

	public function testCanGetMessages()
	{
		$fromId = new \MongoId();
		$toId = new \MongoId();
		$req = new GetUserInboxRequest();
		$req->setFromId($fromId);
		$req->setToId($toId);
		$this->host->init();
		AuthMock::mock();
		$count = $this->inboxRepo->count($fromId);
		$this->createMessage($fromId, $toId);
		$res = MockHostProvider::execute($req);
		$count2 = $this->inboxRepo->count($fromId);
		$this->assertTrue($count2 === ($count + 1));
	}

	public function testCanSendMessage()
	{
		$fromId = new \MongoId();
		$toId = new \MongoId();
		$this->host->init();
		AuthMock::mock();
		$req = new SendMessageRequest();
		$req->setFromId($fromId);
		$req->setToId($toId);
		$req->setMessage('message');
		$res = MockHostProvider::execute($req);

		$messages = $this->inboxRepo->getConversation($fromId, $toId);
		$this->assertTrue(count($messages) > 0);
	}

	protected function createMessage(\MongoId $fromId, \MongoId $toId)
	{
		$entity = new InboxMessage();
		$entity->toId($toId);
		$entity->fromId($fromId);
		$entity->message('test');
		$entity->when(new \DateTime('now'));
		$this->inboxRepo->add($fromId, $toId, $entity);
		return $entity;
	}
}