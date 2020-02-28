# **Build Documentation**

##### _**BUILD DATE:** 24/02/2020_

- Integrated with Eye-d systems Gates
- Performed Demo in Livingstone and showcased system
- Created Demo App for boarded clients ticket authentication

##### _**BUILD DATE:** 22/01/2020_

· Scheduled Route EndPoint, Should accept start_route, end_route and date_of_travel

- Response, in addition to what is currently being returned we need; available seats, departure time and bus_schedule_id | **DONE**
- Purchase Bus Ticket Endpoint: in addition to the attributes given, we should include bus_schedule_id | **DONE**
- Purchase Ticket Endpoint is returning an error when tested | **NEED MORE INFORMATION ABOUT ERRORS ENCOUNTERED**

- We couldn’t check if the number of seats available are changing upon invoking Purchase bus ticket due to the error and non-availability of attribute in scheduled Routes. **BUS SCHEDULES APIs RETURNS SEATS AVAILABLE**
- In the current state of the API, it was not possible to test the three account state (Active, Inactive and OTP).
 This has had a cascading effect to Fetch Marketeer KYC, Authenticate Marketeer, Update Pin and Reset Pin Endpoints as these will depend on the account status of the Marketeers. **WHEN DO THE STATES CHANGE?**
- Pin and Mobile number are not being validated in terms format, length, digits or not. ( Though we are validating these from the APP side, we feel it’s important for same validation to take place on the API side as well) **VALIDATION HAS BEEN ADDED FOR MOBILE NUMBER AND PIN**
- We need to know the length of the Pin that we will be sending, we are also of the view that mobile number should be stored starting with a country code. **PIN LENGTH IS 5 NUMERIC CHARACTERS AND MOBILE NUMBER LENGTH IS 12 CHARACTERS LONG WITH COUNTRY CODE INCLUDED**