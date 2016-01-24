<?hh

namespace Pi\ServiceInterface;

use Pi\Service;
use Pi\ServiceModel\GetUserInboxRequest;
use Pi\ServiceModel\GetUserInboxResponse;
use Pi\ServiceModel\SendMessageRequest;
use Pi\ServiceModel\SendMessageResponse;
use Pi\ServiceModel\Types\InboxMessage;
use Pi\ServiceInterface\Data\UserInboxRepository;
use Pi\Auth\UserRepository;

class UserInboxService extends Service {

	public UserInboxRepository $inboxRepo;

	public UserRepository $userRepo;

	<<Request,Auth,Route('/inbox-view'),Method('POST')>>
	public function get(GetUserInboxRequest $request)
	{
		$response = new GetUserInboxResponse();
		if(is_null($request->getFromId())) {
			$request->setFromId($this->request()->getUserId());
		}
		$messages = $this->inboxRepo->getConversation($request->getFromId(), $request->getToId());
		foreach($messages as $msg){
			$msg->received((string)$msg->fromId() !== (string)$request->getToId());
			$msg->fromId((string)$msg->fromId());

			$msg->toId((string)$msg->toId());
		}

		$otherUser = $this->userRepo->getDto($request->getFromId());
		$response->setOtherUser($otherUser);
		$response->setMessages($messages);
		return $response;
	}

	<<Request,Auth,Route('/inbox'),Method('POST')>>
	public function send(SendMessageRequest $request)
	{
		$response = new SendMessageResponse();
		$message = new InboxMessage();
		$message->fromId($request->getFromId());
		$message->toId($request->getToId());
		$message->message($request->getMessage());
		$message->when(new \DateTime('now'));

		$this->inboxRepo->add($request->getFromId(), $request->getToId(), $message);
		$response->setMessage($message);
		return $response;
	}
}
