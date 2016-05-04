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




class PiPlugins implements IPlugin {

    public function register(IPiHost $appHost) : void 
    {
        $container = $appHost->container();
        $config = $appHost->config();

        /*
        $container->register('Pi\ServiceInterface\AbstractMailProvider', function(IContainer $ioc) use($config) {
            $provider = new SmtpMailProvider($config, $ioc->get('ICacheProvider'));
            //if(!$provider->isCached::class) {
                $provider->loadFromCache();
            //}
            return $provider;
        });
        */
        $container->registerAutoWired('Pi\ServiceInterface\AbstractMailProvider');

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

        $container->registerAutoWired('Pi\ServiceInterface\UserFriendBusiness');
        $container->registerAutoWired('Pi\ServiceInterface\LikesProvider');
        $container->registerAutoWired('Pi\ServiceInterface\UserFollowBusiness');
        $container->registerAutoWired('Pi\ServiceInterface\UserFeedBusiness');
        $container->registerAutoWired('Pi\ServiceInterface\OfferCreateBusiness');

        $appHost->registerService(PlaceService::class);
        $appHost->registerService(MetadataService::class);
        $appHost->registerService(FindUserService::class);
        $appHost->registerService(UserService::class);
        $appHost->registerService(ApplicationService::class);
        $appHost->registerService(UserInboxService::class);
        $appHost->registerService(CarrersService::class);
        $appHost->registerService(AlbumService::class);
        $appHost->registerService(RunPlanService::class);
        $appHost->registerService(CommentService::class);
        $appHost->registerService(ArticleService::class);
        $appHost->registerService(ProductService::class);
        $appHost->registerService(QAService::class);
        $appHost->registerService(LikesService::class);

        $container->registerRepository(UserEntity::class, UserRepository::class);
        $container->registerRepository(Newsletter::class, NewsletterRepository::class);
        $container->registerRepository(NewsletterRepository::class, NewsletterSubscriptionRepository::class);
        $container->registerRepository(Answer::class, AnswerRepository::class);
        $container->registerRepository(AppMessage::class, AppMessageRepository::class);
        $container->registerRepository(Question::class, QuestionRepository::class);
        $container->registerRepository(QuestionCategory::class, QuestionCategoryRepository::class);
        $container->registerRepository(Offer::class, OfferRepository::class);
        $container->registerRepository(Article::class, ArticleRepository::class);
        $container->registerRepository(ArticleSerie::class, ArticleSerieRepository::class);
        $container->registerRepository(ArticleCategory::class, ArticleCategoryRepository::class);
        $container->registerRepository(Place::class, PlaceRepository::class);
        $container->registerRepository(CommentBucket::class, CommentRepository::class);
        $container->registerRepository(RunPlan::class, RunPlanRepository::class);
        $container->registerRepository(AlbumImage::class, AlbumImageRepository::class);
        $container->registerRepository(Product::class, ProductRepository::class);
        $container->registerRepository(Album::class, AlbumRepository::class);
        $container->registerRepository(JobCarrer::class, JobCarrerRepository::class);
        $container->registerRepository(Application::class, ApplicationRepository::class);
        $container->registerRepository(LikesBucket::class, LikesRepository::class);
        $container->registerRepository(AppFeed::class, AppFeedRepository::class);
        $container->registerRepository(UserFeedItem::class, UserFeedItemRepository::class);
        $container->registerRepository(FeedAction::class, FeedActionRepository::class);
        $container->registerRepository(UserFollowBucket::class, UserFollowRepository::class);
        $container->registerRepository(UserFollowersBucket::class, UserFollowersRepository::class);
        $container->registerRepository(UserFriendBucket::class, UserFriendRepository::class);
        $container->registerRepository(UserFriendRequestBucket::class, UserFriendRequestRepository::class);
        $container->registerRepository(MessageBucket::class, UserInboxRepository::class);
    }
  }
