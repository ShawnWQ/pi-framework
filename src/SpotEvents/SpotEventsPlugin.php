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
use SpotEvents\ServiceModel\PaymentReceiveRequest,
    SpotEvents\ServiceModel\CreateEventValidator;
use SpotEvents\ServiceInterface\EventLikesProvider;

class SpotEventsPlugin  implements IPlugin {

    public function register(IPiHost $appHost) : void
    {
        $container = $appHost->container();
        $appHost->registerService(EventsService::class);
        $appHost->registerService(PaymentService::class);
        $appHost->registerService(GymCampaignService::class);
        $appHost->registerService(TicketService::class);
        $appHost->registerService(ModalityService::class);
        $appHost->registerService(NutritionService::class);
        $appHost->registerService(WorkoutService::class);
        $appHost->registerService(OpenWeatherMapService::class);
        $container->registerRepository(NutritionPlan::class, NutritionRepository::class);
        $container->registerRepository(NutritionSerie::class, NutritionSerieRepository::class);
        $container->registerRepository(Workout::class, WorkoutRepository::class);
        $container->registerRepository(WorkoutSerie::class, WorkoutSerieRepository::class);
        $container->registerRepository(Ticket::class, TicketRepository::class);
        $container->registerRepository(EventEntity::class, EventRepository::class, 'event');
        $container->registerRepository(EventAttendantBucket::class, EventAttendantRepository::class);
        $container->registerRepository(EventSportEntity::class, EventSportEntityRepository::class);
        $container->registerRepository(EventSubscription::class, EventSubscriptionRepository::class);
        $container->registerRepository(PaymentEntity::class, PaymentRepository::class);
        $container->registerRepository(GymCampaign::class, GymCampaignRepository::class);
        $container->registerRepository(Modality::class, ModalityRepository::class);
        $container->registerRepository(EventCategory::class, EventCategoryRepository::class);
        $container->registerInstance(EventLikesProvider::class);;
        $container->register('SpotEvents\ServiceInterface\Interfaces\IPaymentProvider', function(IContainer $ioc){
            return new IfThenPaymentProvider();
        });
        //$container->registerValidator('SpotEvents\ServiceModel\CreateEvent', CreateEventValidator::instance::class);

        $appHost->registerSubscriber('SpotEvents\ServiceModel\PaymentReceiveRequest', 'SpotEvents\ServiceModel\EventPaymentReceiveRequest');
    }
  }
