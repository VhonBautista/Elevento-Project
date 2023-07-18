# Capstone-Project-PSU-UC
This repository will be used for the Pangasinan State University capstone project.

# Project Title:
Elevating Event Optimization (ELEVENTO)

# Features:
### USERS 
* Admin 
* Attendee 
* Event Organizer 

## ADMIN 
#### 2 Types of administrators 
* Master Admin 
* Co-Admin 

### Master Admin 
* Login using admin credentials 
* Admin Dashboard 
  * Total number of active, postponed, cancelled events (filter by per campuses) 
  * Total number of events that are for confirmation (filter by per campuses)  
  * Total number of event organizers and attendees (filter by per campuses) 
  * Event monitoring (events that are happening in the current day) 
  * Analytics for optimal date suggestion (based on the trend of attendees from past events) 
  *Weather forecasting for the next 5 days 
* User Management (can manage users and access controls for co-admins. Can manage pending request for being an event organizer) 
* Campus Entities Management (can manage campus entities like departments, organizations, and clubs. This includes the location or the campus it belongs to) 
* Event Management (can manage pending request events waiting for confirmation. Can also see ongoing and upcoming events filtered by type and campus. Can postpone or cancel an event) 
* Venue Management (can manage venue availability) 
* Notification 

### Co-Admin 
* Can do what can admin does but limited (can be set by master admin what resources can the co-admin manage) 

## EVENT ORGANIZER 
* Login and Registration (account validity need to be confirmed by the admin. Registration must include the campus entity that the user belongs to as well as the campus. Users must only be from PSU) 
* Manage Account (manage user account like change password and add profile picture) 
* History (for events that are completed) 
* Projects (will display all the event projects from most recently. Can also view pending events) 
* Dashboard 
  * Event showcase (events that are happening in the current day within the campus in carousel display) 
  * Analytics for optimal date suggestion (based on the trend of attendees from past events) 
  * Calendar of events (including holidays and events made by other organizers within the same campus. Can view a selected date for activities and can create new project with that date) 
  * Weather forecasting for the next 5 days 
  * Button for creating a new project 
  * List of Projects (recent. With smart search and filters) 
*Task Management (view assigned task within projects. Can use accordion) 
* Explore Events (can view all published events within the campus, can be filtered. Also shows a calendar of events) 
* Notifications 

### Requesting an Event 
* Create a Request form (request form requirements. Can be accepted only by the admins. Forms include target audience)
  
### Manage Event 
* Once the request was accepted by the admins, event organizers will now proceed to manage the event. 
* Manage Event Details (date of the event, preview picture, description, main venue) 
* Create a sub event (will depend on how many days the event lasts. Can manage program flow for each sub event. Also, can select venue) 
* Program flow items (can manage program flows. Can add title, short description, and time) 
* Add Event Speakers (dynamically add or remove form inputs for speakers) 
* Manage Registration Form (dynamically add or remove form inputs for questions) 
* Task Management (can assign task to event organizers working on the same project) 
* Collaboration (can invite event organizers using their emails in the project)  

### Finishing Event Planning 
* Finalize and Publish (will make the event active and be shown in the event calendar) 
* Manage Event Status (can postpone the event if changes are needed. Can cancel the event) 
* Share to Social Media platforms 
* View Event Details 
* Event Preview 
* Event Details (number of registered individuals)
  
### During the Event 
* QR Attendance (will scan QR Pass of registered attendees of the event) 
* Attendance tracking (will be able to track the attendees who attend in the given event) 

### Post Event Preview 
* Event Report (number of total attendees and the number of attendees who attend. Registered to attendance ratio) 
* Feedbacks and ratings (view comments about the event as well as the rating)

## ATTENDEE 
* Login and Registration (Users must only be from PSU. Include course detail) 
* Manage Account (manage user account like change password and add profile picture) 
* History (for events that are completed) 
* Saved Events (list of saved events) 
* Register to an Event (can register to events made for the same course as the user or events that includes the course of the user. Upon registration, will generate a unique QR pass for the event which will serve as the attendance pass) 
* Explore Events (can view all published events within the campus, can be filtered. Also shows a calendar of events) 
* Notifications 

### During the Event 
* QR Pass (will use the QR pass generated for that event only, other QR pass will not be valid. QR pass are proofs that the attendee registered for that event) 

### After the Event 
* Feedback and Ratings (attendees can leave ratings and comments for completed events that they attended to) 
