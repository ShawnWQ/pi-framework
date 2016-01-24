<?hh

namespace Pi\FileSystem;

enum FileType : string {
	JPG = 'jpg';
	JPEG = 'jpeg';
	PNG = 'png';
	Docx = 'docx';
	Pdf = 'pdf';
	Mp3 = 'mp3';
	Mp4 = 'mp4';
	Avi = 'avi';
	Mpeg = 'mpeg';
	Mkv = 'mkv';
	M4v = 'm4v';
	// (Multimedia Playlist)
	M3U = 'audio/x-mpegurl';
	// Microsoft Access
	Mdb = 'mdb'; 
	// Microsoft Advanced Systems Format (ASF)
	Asf = 'asf';
	// Microsoft Application
	Exe = 'exe';
	// Microsoft Artgalry
	Cil = 'cil';
	// Microsoft Cabinete File
	Cab = 'cab';
	// Microsoft Application Server
	Ims = 'ims';
	// Microsoft Embedded FontType
	Eot = 'eot';
	// Microsoft Excel
	Xls = 'xls';
	// Microsoft OneNote
	Onetoc = 'onetoc';
	// Microsoft Power Point
	Ppt = 'ppt';
	// Microsoft Publisher
	Pub = 'pub';
	// Silverlight 
	Xap = 'xap';
	// Microsoft Word
	Doc = 'doc';
}