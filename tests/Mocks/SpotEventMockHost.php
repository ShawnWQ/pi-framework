<?hh


namespace Mocks;

use Pi\Interfaces\IContainer;
use SpotEvents\SpotEventsPlugin;
use SpotEvents\ServiceInterface\Data\PaymentRepository;
use SpotEvents\ServiceInterface\EventsService;
use SpotEvents\ServiceInterface\Data\EventSportEntityRepository;
use SpotEvents\ServiceInterface\Data\EventSubscriptionRepository;
use SpotEvents\ServiceInterface\Data\EventRepository;
use SpotEvents\ServiceInterface\IfThenPaymentProvider;
use SpotEvents\ServiceInterface\PaymentService;
use SpotEvents\ServiceModel\Types\EventSportEntity;
use SpotEvents\ServiceModel\Types\EventEntity;
use SpotEvents\ServiceModel\Types\EventSubscription;
use SpotEvents\ServiceModel\Types\PaymentEntity;

class SpotEventMockHost  extends BibleHost {

    public function configure(IContainer $container)
    {
    	parent::configure($container);
        $this->registerPlugin(new SpotEventsPlugin());
    }
  }
