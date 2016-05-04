<?hh

namespace Pi\FileSystem;
use Pi\Service;
use Pi\Common\RandomString;
use Pi\ServiceModel\ListFilesRequest;
use Pi\ServiceModel\ListFilesResponse;
use Pi\PiFileMapper;

class FileSystemService extends Service {

	public FileSystemConfiguration $config;

	public FileEntityRepository $repository;

	public function __construct()
	{
		parent::__construct();
	}

	<<Dependency('FileSystemConfiguration')>>
	public function config($value = null)
	{
		if($value === null) return $this->config;
		$this->config = $value;
	}

	/**
	 * @param FileGet $request
	 */
	<<Request,Method('GET'),Route('/files-pub/')>>
	public function get(FileGet $request)
	{

	}

	<<Request,Method('GET'),Route('/files-list')>>
	public function ls(ListFilesRequest $request)
	{
		$response = new ListFilesResponse();

		$fileMapper = new PiFileMapper(Set{$request->getDirPath()});
		$map = $fileMapper->getMap();

		$response->setFiles($map);
		return $response;
	}

	<<Request,Method('GET'),Route('/files')>>
	public function query(FileFind $request)
	{
		$files = $this->repository->queryBuilder('Pi\FileSystem\File')
			->find()
			->hydrate()
			->limit($request->getLimit())
			->skip($request->getSkip())
			->sort('_id', -1)
			->getQuery()
			->execute();

		$response = new FileFindResponse($files);

		return $response;
	}

	<<Request,Method('GET'),Route('/files/:fileId')>>
	public function getPublic(FileGet $request)
	{
		$entity = $this->repository->get($request->fileId());

		$response = new FileGetResponse();
  		$response->fileName($entity->fileName());

		return $entity;
	}


	<<Request,Method('POST'),Route('/filesystem')>>
	public function upload(FileUpload $request)
	{
		$fileNameToken = RandomString::generate(20);

		$fileName = isset($request->displayName()) ? $request->displayName() : $fileExt = pathinfo($request->file()->name(), PATHINFO_FILENAME);;
		$fileExt = pathinfo($request->file()->name(), PATHINFO_EXTENSION);
		$fileName .=  '_' . $fileNameToken;

		$savePath = $this->config->storeDir() . '/' . $fileName  . '.' . $fileExt;

		$entity = new FileEntity();
		$entity->name($fileName  . '.' . $fileExt);
		$entity->ownerToken((string)$tokenId);
		$entity->fileName($savePath);
		$entity->extension($fileExt);
		$protocol = $this->appConfig()->protocol();
		$uri = sprintf('%s://%s/cdn/%s.%s', $protocol, $this->appConfig()->domain(), (string)$fileName, $fileExt);
		$entity->uri($uri);
		$entity->url($uri);

		if($this->request()->isAuthenticated()) {
			$entity->ownerId($this->request()->getUserId());
		}

		$this->repository->insert($entity);
		$this->repository->commit();

		move_uploaded_file($request->file()->tmpName(), $savePath);

		if($request->generateThumbnail()) {
			$sizes = array(50 => 50, 150 => 150, 300 => 300);
			foreach($sizes as $k => $v) {
				$thumb = sprintf('%s/%s-%dx%d.%s', $this->config->storeDir(), $fileName, $k, $v, $fileExt);
				$image = new \Imagick($savePath);
				$image->thumbnailImage($k, 0);
				$image->writeImage($thumb);
			}
		}

		$response = new FileUploadResponse();
		$response->fileId((string)$entity->id());
		$response->ownerToken($tokenId);
		$response->uri($uri);
		$response->uriPublic(sprintf('%s://%s/files/%s', $protocol, $this->appConfig()->domain(), (string)$entity->id(), $fileExt));

		return $response;
	}

	private function getTokenId(string $fileName)
	{
		return current(array_slice(explode("#", $fileName), 0,1));
	}
}
