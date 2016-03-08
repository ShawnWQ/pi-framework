<?hh

namespace Pi\FileSystem;

class FileExtensions {

	public static function getFileExtension(string $fileName)
	{
		return end((explode(".", $fileName))); # extra () to prevent notice
	}

	public static function extensionIsImage(string $extension)
	{
		return in_array(array(FileType::JPG, FileType::JPEG, FileType::PNG), $extension);
	}

	public static function extensionIsVideo(string $extension)
	{
		return in_array(array(FileType::Mp4, FileType::Mkv, FileType::Avi), $extension);
	}

	public static function convertRequestFilesToModels() : ?array
	{
		$response = array();
		foreach($_FILES as $key => $data){
	        $file = new \Pi\FileSystem\File();
	        $file->tmpName($data['tmp_name']);
	        $file->name($data['name']);
	        $file->type($data['type']);
						switch(FileExtensions::getFileExtension($data['name'])) {

							case FileType::JPEG:
							$file->extension(FileType::JPG);
							break;

							case FileType::JPG:
							$file->extension(FileType::JPG);
							break;

							case FileType::PNG:
							$file->extension(FileType::PNG);
							break;

							case FileType::Pdf:
							$file->extension(FileType::Pdf);
							break;

							case FileType::Mp3:
							$file->extension(FileType::Mp3);
							break;

							case FileType::Mp4:
							$file->extension(FileType::Mp4);
							break;

							case FileType::Mkv:
							$file->extension(FileType::Mkv);
							break;

							case FileType::Avi:
							$file->extension(FileType::Avi);
							break;

							default:
//							throw new \Exception('File extension not supported');
						}
	        $response[] = $file;
	      }
	    

	    return count($response > 0) ? $response : null;
	}
}
