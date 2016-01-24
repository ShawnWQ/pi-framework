## Filters

When configuring an application you can add filters to certain execution phases.



### Non HTTP

The application may execute requests internally (the Service being executed can execute another service, the message queue between services, etc). For non HTTP requests only the Global filters are executed and filters attributes

### Filter Attributes

Filter attributes are for cases where you don't want to register an callback in ApplicationHost. Usually you'll want to use one when you're doing to much code on the callbacks.

Filters have properties injected, which mean they can execute others requests, check for the current operation state, ec tc.
