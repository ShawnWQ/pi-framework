<?hh

namespace Pi\Queue;




enum JobStatus : string {
	StatusRunning = 'status_running';
	StatusComplete = 'status_complete';
	StatusFailed = 'status_failed';
}