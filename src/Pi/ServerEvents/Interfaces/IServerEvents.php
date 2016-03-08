<?hh

namespace Pi\ServerEvents\Interfaces;

use Pi\Interfaces\IDisposable;

interface IServerEvents extends IDisposable
{
    // External API's
    public function notifyAll(string $selector, object $message) : void;

    public function notifyChannel(string $channel, string $selector, object $message) : void;

    public function notifySubscription(string $subscriptionId, string $selector, string $message, ?object $channel = null) : void;

    public function notifyUserId(string $userId, string $selector, object $message, ?string $channel = null) : void;

    public function notifyUserName(string $userName, string $selector, object $message, ?string $channel = null) : void;

    public function notifySession(string $pipid, string $selector, object $message, ?string $channel = null) : void;

    public function getSubscriptionInfo(string $id) : SubscriptionInfo;

    public function getSubscriptionInfosByUserId(string $userId) : Vector<SubscriptionInfo>;

    // Admin API's
    public function register(IEventSubscription $subscription, ?Map<string,string> $connectArgs = null);

    public function unRegister(string $subscriptionId) : void;

    public function getNextSequence(string $sequenceId);

    // Client API's
    public function getSubscriptionsDetails(?string $channel = null) : Vector<Map<string,string>>;

    public function pulse(string $subscriptionId) : bool;

    // Clear all Registrations
    public function reset() : void;
    public function start() : void;
    public function stop() : void;
}
