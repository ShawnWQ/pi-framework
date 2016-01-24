<?hh


namespace SpotEvents;
use Pi\AppHost;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IPreInitPlugin;
use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IPiHost;
use SpotEvents\ServiceInterface\Data\PaymentRepository;
use SpotEvents\ServiceInterface\Data\ModalityRepository;
use SpotEvents\ServiceInterface\ModalityService;
use SpotEvents\ServiceInterface\EventsService;
use SpotEvents\ServiceInterface\GymCampaignService;
use SpotEvents\ServiceInterface\Data\EventSportEntityRepository;
use SpotEvents\ServiceInterface\Data\EventSubscriptionRepository;
use SpotEvents\ServiceInterface\Data\EventRepository;
use SpotEvents\ServiceInterface\Data\EventAttendantRepository;
use SpotEvents\ServiceInterface\Data\GymCampaignRepository;
use SpotEvents\ServiceInterface\Data\TicketRepository;
use SpotEvents\ServiceInterface\Data\NutritionRepository;
use SpotEvents\ServiceInterface\Data\NutritionSerieRepository;
use SpotEvents\ServiceInterface\Data\WorkoutRepository;
use SpotEvents\ServiceInterface\Data\WorkoutSerieRepository;
use SpotEvents\ServiceInterface\Data\EventCategoryRepository;
use SpotEvents\ServiceInterface\IfThenPaymentProvider;
use SpotEvents\ServiceInterface\TicketService;
use SpotEvents\ServiceInterface\OpenWeatherMapService;
use SpotEvents\ServiceInterface\PaymentService;
use SpotEvents\ServiceInterface\NutritionService;
use SpotEvents\ServiceInterface\WorkoutService;
use SpotEvents\ServiceModel\Types\Modality;
use SpotEvents\ServiceModel\Types\EventSportEntity;
use SpotEvents\ServiceModel\Types\EventEntity;
use SpotEvents\ServiceModel\Types\EventSubscription;
use SpotEvents\ServiceModel\Types\PaymentEntity;
use SpotEvents\ServiceModel\Types\GymCampaign;
use SpotEvents\ServiceModel\Types\EventAttendantBucket;
use SpotEvents\ServiceModel\Types\Ticket;
use SpotEvents\ServiceModel\Types\NutritionPlan;
use SpotEvents\ServiceModel\Types\NutritionSerie;
use SpotEvents\ServiceModel\Types\Workout;
use SpotEvents\ServiceModel\Types\WorkoutSerie;
use SpotEvents\ServiceModel\Types\EventCategory;
use SpotEvents\ServiceModel\PaymentReceiveRequest;
use SpotEvents\ServiceInterface\EventLikesProvider;

class SpotEventsPlugin  implements IPlugin {

    public function configure(IPiHost $appHost) : void
    {
        $container = $appHost->container();
        $appHost->registerService(new EventsService());
        $appHost->registerService(new PaymentService());
        $appHost->registerService(new GymCampaignService());
        $appHost->registerService(new TicketService());
        $appHost->registerService(new ModalityService());
        $appHost->registerService(new NutritionService());
        $appHost->registerService(new WorkoutService());
        $appHost->registerService(new OpenWeatherMapService());

        $redis = $container->get('IRedisClientsManager');
        $container->registerRepository(new NutritionPlan(), new NutritionRepository());
        $container->registerRepository(new NutritionSerie(), new NutritionSerieRepository());
        $container->registerRepository(new Workout(), new WorkoutRepository());
        $container->registerRepository(new WorkoutSerie(), new WorkoutSerieRepository());
        $container->registerRepository(new Ticket(), new TicketRepository());
        $container->registerRepository(new EventEntity(), new EventRepository(), 'event');
        $container->registerRepository(new EventAttendantBucket(), new EventAttendantRepository($redis));
        $container->registerRepository(new EventSportEntity(), new EventSportEntityRepository());
        $container->registerRepository(new EventSubscription(), new EventSubscriptionRepository());
        $container->registerRepository(new PaymentEntity(), new PaymentRepository());
        $container->registerRepository(new GymCampaign(), new GymCampaignRepository());
        $container->registerRepository(new Modality(), new ModalityRepository());
        $container->registerRepository(new EventCategory(), new EventCategoryRepository());

        $container->registerInstance(new EventLikesProvider());;
        $container->register('SpotEvents\ServiceInterface\Interfaces\IPaymentProvider', function(IContainer $ioc){
            return new IfThenPaymentProvider();
        });

        $appHost->registerSubscriber('SpotEvents\ServiceModel\PaymentReceiveRequest', 'SpotEvents\ServiceModel\EventPaymentReceiveRequest');
    }
  }
