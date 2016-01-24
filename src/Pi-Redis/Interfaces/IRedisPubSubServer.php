<?hh
use Pi\Interfaces\IDisposable;

interface IRedisPubSubServer extends IDisposable
{
    public function glientsManager() : IRedisClientsManager;
    // What Channels it's subscribed to
    public function channels() : Set<string>;

    // Run once on initial StartUp
    public function onInit() : void;

    // Called each time a new Connection is Started
    public function onStart() : void;

    /**
     * Invoked when Connection is broken or Stopped
     */
    public function onStop() : void;
    /**
     * Invoked after Dispose()
     */
    public function onDispose() : void;
    
    // Fired when each message is received
    Action<string, string> OnMessage { get; set; }
    // Fired after successfully subscribing to the specified channels
    Action<string> OnUnSubscribe { get; set; }
    // Called when an exception occurs
    Action<Exception> OnError { get; set; }
    // Called before attempting to Failover to a new redis master
    Action<IRedisPubSubServer> OnFailover { get; set; }

    int? KeepAliveRetryAfterMs { get; set; }
    // The Current Time for RedisServer
    DateTime CurrentServerTime { get; }

    // Current Status: Starting, Started, Stopping, Stopped, Disposed
    string GetStatus();
    // Different life-cycle stats
    string GetStatsDescription();

    // Subscribe to specified Channels and listening for new messages
    IRedisPubSubServer Start();
    // Close active Connection and stop running background thread
    void Stop();
    // Stop than Start
    void Restart();
}
