<?hh

namespace Pi\ServiceInterface;

use Pi\Service;
use Pi\HttpResult;
use Pi\ServiceModel\PostArticleSerieRequest;
use Pi\ServiceModel\PostArticleSerieResponse;
use Pi\ServiceModel\RemoveArticleRequest;
use Pi\ServiceModel\RemoveArticleResponse;
use Pi\ServiceModel\RemoveArticleCategoryRequest;
use Pi\ServiceModel\RemoveArticleCategoryResponse;
use Pi\ServiceModel\GetArticleRequest;
use Pi\ServiceModel\GetArticleResponse;
use Pi\ServiceModel\GetArticleSerieRequest;
use Pi\ServiceModel\GetArticleSerieResponse;
use Pi\ServiceModel\RemoveArticleSerieRequest;
use Pi\ServiceModel\RemoveArticleSerieResponse;
use Pi\ServiceModel\PostArticleRequest;
use Pi\ServiceModel\PostArticleResponse;
use Pi\ServiceModel\PutArticleRequest;
use Pi\ServiceModel\PutArticleResponse;
use Pi\ServiceModel\FindArticleRequest;
use Pi\ServiceModel\FindArticleResponse;
use Pi\ServiceModel\FindArticleSerieRequest;
use Pi\ServiceModel\PostArticleCategoryRequest;
use Pi\ServiceModel\PostArticleCategoryResponse;
use Pi\ServiceModel\ArticleDto;
use Pi\ServiceModel\ArticleSerieDto;
use Pi\ServiceModel\ArticleCategoryDto;
use Pi\ServiceModel\Types\Article;
use Pi\ServiceModel\Types\ArticleSerie;
use Pi\ServiceModel\Types\ArticleSerieEmbed;
use Pi\ServiceModel\Types\ArticleCategory;
use Pi\ServiceInterface\Data\ArticleRepository;
use Pi\ServiceInterface\Data\ArticleSerieRepository;
use Pi\ServiceInterface\Data\ArticleCategoryRepository;
use Pi\Common\ClassUtils;
use Pi\ServiceModel\Types\FeedAction;
use Pi\ServiceInterface\UserFriendBusiness;
use Pi\ServiceInterface\UserFeedBusiness;
use Pi\ServiceModel\ArticleState;


<<Route('/article')>>
class ArticleService extends AbstractCreativeWorkService {

	public function init()
	{
		$this->registerCustomType(Article::TYPE, 'art');
	}

}
