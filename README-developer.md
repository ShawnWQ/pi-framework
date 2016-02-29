Developer README
====================

## Order of Operations

This list shows the order which any user-defined custom hooks are executed

### Http Custom hooks

- PiHostInterface.preRequestFilters get executed before the Request DTO is deserialized
- Request Filter Attributes with Priority < 0 gets executed
- Global Request Filters get executed
- Request Filter Attributes with Priority >= 0 gets executed
- Action Request Filters get executed
- The **Service** is executed with the configured ServiceRunnerInterface and its **onBeforeExecute**, **onAfterExecute** and **handleException** custom hooks are fired
- Action Response Filters get executed
- Response Filter Attributes with Priority < 0 get executed
- Global Response Filters get executed
- Response Filter Attributes with Priority >= 0 get executed
- At the end of the Request PiHostInterface.onEndRequest and PiHostInterface.onEndRequestCallbacks are fired

### Message Queue Custom hooks

- Global Request Filters get executed
- Request Filter Attributtes with priority >= 0 get executed
- Action Request Filters are executed
- The **Service** is executed with the configured ServiceRunnerInterface and its **onBeforeExecute**, **onAfterExecute** and **handleException** custom hooks are fired
- Action Response Filters get executed
- Global Response Filters get executed
- At the end of the Request PiHostInterface.onEndRequest is fired

## Implementation architecture

### Request Pipeline

*PiHttpHandlerFactory* - factory for http and cgi handlers

- Is Metadata Handler= - MetadataHandler
- Predefined Route and Built-In format - SpecificTypeHandler and GenericHandler
- Is Physical File - StaticFileHandler
- Matches Custom Route - RestHandler
- catchAllHandlers - CatchAllHandler and NotFoundHandler

#### Rest Handler

- Is Content Type Disabled? - NotSupported
- Bind to Request DTO
- Is Handled by Request Filter - RequestFilter
- Execute Request
- If was exception wrap in HttpErrorInterface
- Is Handled by Response Filter - ResponseFilter
- Is HttpResultInterface - Write HTTP Headers
- Is String? - Write 
- Serialize DTO
- If was serialization exception - Write Error

## Core

### Handlers

This list shows the Process Request

- Pre Request Filters are executed, if any then return
- Resolve the restPath, if null throw
- Set the Route of RequestInterface 
- Set the ResponseContentType if any
- Call **createRequest** and assign the DTO to RequestInterface
- Request Filters are executed, if any then return
- Call **getResponse** to get Response
- Call **handleResponse** that:
 - Response Filters are executed
 - return ResponseInterface.writeToResponse

## Reflection

**class_exists** by default calls __autoload of class, which most of the times isn't wanted.