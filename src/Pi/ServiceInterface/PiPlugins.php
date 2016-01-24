<?hh

namespace Pi\ServiceInterface;

use Pi\Interfaces\IPlugin,
    Pi\Interfaces\IPreInitPlugin,
    Pi\Interfaces\IPiHost,
    Pi\Interfaces\IContainer,
    Pi\Interfaces\IPluginServiceRegister,
    Pi\ServiceInterface\LikesService,
    Pi\ServiceInterfaces\OpeningHoursBusiness,
    Pi\ServiceInterface\LikesProvider,
    Pi\ServiceInterface\ArticleService,
    Pi\ServiceInterface\FindUserService,
    Pi\ServiceInterface\UserInboxService,
    Pi\ServiceInterface\ApplicationService,
    Pi\ServiceInterface\UserService,
    Pi\ServiceInterface\CommentService,
    Pi\ServiceInterface\CarrersService,
    Pi\ServiceInterface\AlbumService,
    Pi\ServiceInterface\ProductService,
    Pi\ServiceInterface\QAService,
    Pi\ServiceInterface\PlaceService,
    Pi\ServiceInterface\RunPlanService,
    Pi\ServiceInterface\MetadataService,
    Pi\ServiceInterface\Data\QuestionRepository,
    Pi\ServiceInterface\Data\QuestionCategoryRepository,
    Pi\ServiceInterface\Data\AnswerRepository,
    Pi\ServiceInterface\Data\OfferRepository,
    Pi\ServiceInterface\Data\NewsletterRepository,
    Pi\ServiceInterface\Data\NewsletterSubscriptionRepository,
    Pi\ServiceInterface\Data\ArticleRepository,
    Pi\ServiceInterface\Data\ArticleSerieRepository,
    Pi\ServiceInterface\Data\ArticleCategoryRepository,
    Pi\ServiceInterface\Data\PlaceRepository,
    Pi\ServiceInterface\Data\CommentRepository,
    Pi\ServiceInterface\Data\ProductRepository,
    Pi\ServiceInterface\Data\AlbumRepository,
    Pi\ServiceInterface\Data\AlbumImageRepository,
    Pi\ServiceInterface\Data\JobCarrerRepository,
    Pi\ServiceInterface\Data\ApplicationRepository,
    Pi\ServiceInterface\Data\LikesRepository,
    Pi\ServiceInterface\Data\AppFeedRepository,
    Pi\ServiceInterface\Data\UserFeedRepository,
    Pi\ServiceInterface\Data\UserFriendRepository,
    Pi\ServiceInterface\Data\UserInboxRepository,
    Pi\ServiceInterface\Data\UserFriendRequestRepository,
    Pi\ServiceInterface\Data\UserFollowRepository,
    Pi\ServiceInterface\Data\UserFollowersRepository,
    Pi\Auth\UserRepository,
    Pi\ServiceInterface\Data\FeedActionRepository,
    Pi\ServiceInterface\Data\AppMessageRepository,
    Pi\ServiceInterface\Data\UserFeedItemRepository,
    Pi\ServiceInterface\Data\RunPlanRepository,
    Pi\ServiceModel\Types\Place,
    Pi\ServiceModel\Types\AppMessage,
    Pi\ServiceModel\Types\ArticleCategory,
    Pi\ServiceModel\Types\Order,
    Pi\ServiceModel\Types\Question,
    Pi\ServiceModel\Types\QuestionCategory,
    Pi\ServiceModel\Types\Answer,
    Pi\ServiceModel\Types\Offer,
    Pi\ServiceModel\Types\Article,
    Pi\ServiceModel\Types\ArticleSerie,
    Pi\ServiceModel\Types\AppFeed,
    Pi\ServiceModel\Types\UserFeedBucket,
    Pi\Auth\UserEntity,
    Pi\ServiceModel\Types\Newsletter,
    Pi\ServiceModel\Types\NewsletterSubscription,
    Pi\ServiceModel\Types\Application,
    Pi\ServiceModel\Types\LikesBucket,
    Pi\ServiceModel\Types\UserFriendBucket,
    Pi\ServiceModel\Types\UserFriendRequestBucket,
    Pi\ServiceModel\Types\UserFollowersBucket,
    Pi\ServiceModel\Types\UserFollowBucket,
    Pi\ServiceModel\Types\MessageBucket,
    Pi\ServiceModel\Types\JobCarrer,
    Pi\ServiceModel\Types\FeedAction,
    Pi\ServiceModel\Types\UserFeedItem,
    Pi\ServiceModel\Types\Album,
    Pi\ServiceModel\Types\AlbumImage,
    Pi\ServiceModel\Types\RunPlan,
    Pi\ServiceModel\Types\CommentBucket,
    Pi\ServiceModel\Types\Product,
    Pi\Queue\PiQueue,
    Pi\Queue\RedisPiQueue;


class PiPlugins  implements IPlugin, IPreInitPlugin {

    public function configure(IPiHost $appHost) : void {
        $container = $appHost->container();
        $config = $appHost->config();


        $container->register('Pi\ServiceInterface\AbstractMailProvider', function(IContainer $ioc) use($config) {
            $provider = new SmtpMailProvider($config, $ioc->get('ICacheProvider'));
            //if(!$provider->isCached()) {
                $provider->loadFromCache();
            //}
            return $provider;
        });

        $container->register('Pi\ServiceInterface\WordpressCrawler', function(IContainer $ioc) {
            $factory = $ioc->get('Pi\Interfaces\ILogFactory');
            $logger = $factory->getLogger(WordpressCrawler::NAME);
            $instance = new WordpressCrawler($ioc->get('ICacheProvider'), $logger);
            return $instance;
        });

        $container->register('Pi\ServiceInterface\SocialStaticsService', function(IContainer $ioc) {
            $factory = $ioc->get('Pi\Interfaces\ILogFactory');
            $logger = $factory->getLogger(SocialStaticsService::NAME);
            $svc = new SocialStaticsService($logger);
            return $svc;
        });
        $appHost->registerService(new PlaceService());
        $appHost->registerService(new MetadataService());
        $appHost->registerService(new FindUserService());
        $appHost->registerService(new UserService());
        $appHost->registerService(new ApplicationService());
        $appHost->registerService(new UserInboxService());
        $appHost->registerService(new CarrersService());
        $appHost->registerService(new AlbumService());
        $appHost->registerService(new RunPlanService());
        $appHost->registerService(new CommentService());
        $appHost->registerService(new ArticleService());
        $appHost->registerService(new ProductService());
        $appHost->registerService(new QAService());
        $appHost->registerService(new LikesService());


        $redis = $container->get('IRedisClientsManager');

        $container->registerRepository(new Newsletter(), new NewsletterRepository());
        $container->registerRepository(new NewsletterRepository(), new NewsletterSubscriptionRepository($redis));
        $container->registerRepository(new Answer(), new AnswerRepository());
        $container->registerRepository(new AppMessage(), new AppMessageRepository());
        $container->registerRepository(new Question(), new QuestionRepository());
        $container->registerRepository(new QuestionCategory(), new QuestionCategoryRepository());
        $container->registerRepository(new Offer(), new OfferRepository());
        $container->registerRepository(new Article(), new ArticleRepository());
        $container->registerRepository(new ArticleSerie(), new ArticleSerieRepository());
        $container->registerRepository(new ArticleCategory(), new ArticleCategoryRepository());
        $container->registerRepository(new Place(), new PlaceRepository());
        $container->registerRepository(new CommentBucket(), new CommentRepository());
        $container->registerRepository(new RunPlan(), new RunPlanRepository());
        $container->registerRepository(new AlbumImage(), new AlbumImageRepository());
        $container->registerRepository(new Product(), new ProductRepository());
        $container->registerRepository(new Album(), new AlbumRepository());
        $container->registerRepository(new JobCarrer(), new JobCarrerRepository());
        $container->registerRepository(new Application(), new ApplicationRepository());

        $container->registerRepository(new LikesBucket(), new LikesRepository($redis));
        $container->registerRepository(new AppFeed(), new AppFeedRepository($redis));
        $container->registerRepository('Pi\ServiceModel\Types\UserFeedItem', new UserFeedItemRepository($redis));
        $container->registerRepository('Pi\ServiceModel\Types\FeedAction', new FeedActionRepository($redis));
        $container->registerRepository(new UserFollowBucket(), new UserFollowRepository($redis));
        $container->registerRepository(new UserFollowersBucket(), new UserFollowersRepository($redis));
        $container->registerRepository(new UserFriendBucket(), new UserFriendRepository($redis));
        $container->registerRepository(new UserFriendRequestBucket(), new UserFriendRequestRepository($redis));
        $container->registerRepository(new MessageBucket(), new UserInboxRepository($redis));
    }
  }
