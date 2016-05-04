<?hh


namespace SpotEvents;
use Pi\AppHost;
use Pi\Interfaces\IContainer;
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

class SpotEventsHost  extends AppHost {

    public function configure(IContainer $container)
    {
        $this->registerPlugin(new SpotEventsPlugin());
    }
  }
