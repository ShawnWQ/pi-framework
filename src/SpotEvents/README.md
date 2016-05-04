
/EventMan
  AppHost.cs                  //The ServiceStack ASP.NET Web or Console Host Project

/EventMan.ServiceInterface     //All Service implementations (akin to MVC Controllers)
  EventsService.cs
  EventsReviewsService.cs

/EventMan.Logic                //For larger projs: pure C# logic deps, data models, etc
  IGoogleCalendarGateway      //E.g of a external dependency this project could use

/EventMan.ServiceModel         //Service Request/Response DTOs and DTO types in /Types
  Events.cs                   //Events, CreateEvent, GetEvent, UpdateEvent DTOs
  EventReviews.cs             //EventReviews, GetEventReview, CreateEventReview DTOs
  /Types
    Event.cs                  //Event type
    EventReview.cs            //EventReview type
