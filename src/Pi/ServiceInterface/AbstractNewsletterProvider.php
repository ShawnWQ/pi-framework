<?hh

namespace Pi\ServiceInterface;

use Pi\ServiceModel\Types\Newsletter;
use Pi\ServiceModel\Types\NewsletterSubscription;
use Pi\ServiceInterface\Data\NewsletterRepository;
use Pi\ServiceInterface\Data\NewsletterSubscriptionRepository;




abstract class AbstractNewsletterProvider {

    public function __construct(
            protected NewsletterRepository $repo,
            protected NewsletterSubscriptionRepository $subsRepo
        )
    {

    }

    public function notify()
    {

    }

    public function subscriptionExists(\MongoId $newsletterId, \MongoId $userId)
    {
        return $this->subsRepo->isAttending($newsletterId, $userId);
    }


    public function subscribe(\MongoId $newsletterId, \MongoId $userId, string $name, string $email, ?string $reference = null)
    {
        $subs = new NewsletterSubscription();
        $subs->setId($userId);
        $subs->setName($name);
        $subs->setEmail($email);
        if(!is_null($reference)) {
            $subs->setReference($reference);
        }

        $this->subsRepo->add($newsletterId, $subs);
        return $subs;
    }

    public function getSubscriptions(\MongoId $newsletterId)
    {
        return $this->subsRepo->get($newsletterId);
    }

    public function createNewsletter(Newsletter $dto)
    {
        $this->repo->insert($dto);
    }

    public function saveNewsletter()
    {

    }

    public function rescheduleNewsletter()
    {

    }
}
