<?hh

namespace Pi\FileSystem;

enum FileMimeType  : string {
	MPEG = 'audio/mpeg';
	JPG = 'image/jpeg';
	PNG = 'image/png';
	JPEG = 'image/jpeg';
	M4v = 'video/x-m4v';
	M3U = 'audio/x-mpegurl';
	MicrosoftAccess = 'application/x-msaccess';
	MicrosoftASF = 'video/x-ms-asf';
	MicrosoftApplication = 'application/x-msdownload';
	Microsoft Artgalry = 'application/vnd.ms-artgalry';
	MicrosoftCabinetFile = 'application/vnd.ms-cab-compressed';
	MicrosoftApplicationServer = 'application/vnd.ms-ims';
	MicrosoftEmbeddedOpenType = 'application/vnd.ms-fontobject';
	MicrosoftExcell = 'application/vnd.ms-excel';
	MicrosoftOneNote = 'application/onenote';
	MicrosoftPowerPoint = 'application/vnd.ms-powerpoint';
	MicrosoftPublisher = 'application/x-mspublisher';
	MicrosoftSilverlight = 'application/x-silverlight-app';
	MicrosoftWord = 'application/msword';
	PDF = 'application/pdf';
}