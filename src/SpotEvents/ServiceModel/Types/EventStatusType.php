<?hh

namespace Pi\EventPlugin\Domain;

enum EventStatusType: int {
	// The event has been cancelled. If the event has multiple startDate values, all are assumed to be cancelled. Either startDate or previousStartDate may be used to specify the event's cancelled date(s).
	EventCancelled = 0;
	// The event has been postponed and no new date has been set. The event's previousStartDate should be set.
	EventPostponed = 1;
	// The event has been rescheduled. The event's previousStartDate should be set to the old date and the startDate should be set to the event's new date. (If the event has been rescheduled multiple times, the previousStartDate property may be repeated).
	EventRescheduled = 2;
	// The event is taking place or has taken place on the startDate as scheduled. Use of this value is optional, as it is assumed by default.
	EventScheduled = 3;
}
