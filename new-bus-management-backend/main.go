package main

import (
	"fmt"
	"log"
	"net/http"
	"new-bus-management-backend/controllers"
	"new-bus-management-backend/logs"
	"os"
	"time"

	"github.com/gorilla/handlers"
	"github.com/gorilla/mux"
)

func main() {
	if err := setTimezone("Africa/Cairo"); err != nil {
		log.Fatal(err)
	}
	t := getTime(time.Now())
	fmt.Println(t)

	router := mux.NewRouter().StrictSlash(true)
	//router.Use(a.JwtAuthentication)
	f, err := os.OpenFile(""+
		"activity.log",
		os.O_APPEND|os.O_CREATE|os.O_WRONLY, 0644)
	if err != nil {
		log.Println(err)
	}
	defer f.Close()

	log.SetOutput(f)
	log.SetPrefix("LOG: ")
	log.SetFlags(log.Ldate | log.Lmicroseconds | log.Llongfile)
	log.Println("init started")

	// Reset Slots table
	go controllers.InitMidNight()
	go controllers.RunAccessControl()
	go controllers.RunSMSNotifications()

	// authentication routes
	router.Handle("/main/api/user/register", controllers.CreateUserController).Methods("POST")
	router.Handle("/main/api/login", controllers.AuthenticateUserController).Methods("POST")
	router.Handle("/main/api/auth/changepassword", controllers.ChangePassword).Methods("POST")
	router.Handle("/main/api/auth/resetpassword", controllers.ResetPassword).Methods("POST")
	router.Handle("/main/api/access/permissions/{id}", controllers.UpdateAccessPermissionController).Methods("Put")
	router.Handle("/main/api/users", controllers.GetAllUserController).Methods("GET")

	// reservations routes
	router.Handle("/main/api/reservation/requests/create", controllers.CreateReservationController).Methods("POST")
	router.Handle("/main/api/reservation/get", controllers.GetReservationsController).Methods("GET")
	router.Handle("/main/api/reservation/get/{id}", controllers.GetReservationsForController).Methods("GET")
	router.Handle("/main/api/reservation/history/get/{id}", controllers.GetReservationsHistoryForController).Methods("GET")

	// arrival reservations routes
	router.Handle("/main/api/arreservation/requests/create", controllers.ArCreateReservationController).Methods("POST")
	router.Handle("/main/api/arreservation/get", controllers.ArGetReservationsController).Methods("GET")
	router.Handle("/main/api/arreservation/get/{id}", controllers.ArGetReservationsForController).Methods("GET")
	router.Handle("/main/api/arreservation/history/get/{id}", controllers.ArGetReservationsHistoryForController).Methods("GET")

	// Slots routes
	router.Handle("/main/api/slots/create", controllers.CreateSlotController).Methods("POST")
	router.Handle("/main/api/slots/get", controllers.GetSlotsController).Methods("GET")
	router.Handle("/main/api/slots/getbydate/{date}", controllers.GetSlotsByDateController).Methods("GET")
	router.Handle("/main/api/slots/update/{id}", controllers.UpdateSlotController).Methods("PUT")
	router.Handle("/main/api/slots/charge/{id}", controllers.GetLoadingFeeController).Methods("GET")

	// Arrival Slots routes
	router.Handle("/main/api/arslots/create", controllers.ArCreateSlotController).Methods("POST")
	router.Handle("/main/api/arslots/get", controllers.ArGetSlotsController).Methods("GET")
	router.Handle("/main/api/arslots/getbydate/{date}", controllers.ArGetSlotsByDateController).Methods("GET")
	router.Handle("/main/api/arslots/update/{id}", controllers.ArUpdateSlotController).Methods("PUT")

	// Reservations Approval routes
	router.Handle("/main/api/reservations/requests", controllers.GetReservationsRequestsController).Methods("GET")
	router.Handle("/main/api/reservations/requests/history", controllers.GetReservationsRequestsHistoryController).Methods("GET")
	//router.Handle("/main/api/reservations/requests/history/range", controllers.GetReservationsRequestsHistoryController).Methods("GET")
	router.Handle("/main/api/approve/reservations/requests/{id}", controllers.UpdateReservationController).Methods("PUT")
	router.Handle("/main/api/slots/close", controllers.CloseReservationController).Methods("PUT")

	// Arrival Reservations Approval routes
	router.Handle("/main/api/arreservations/requests", controllers.GetARReservationsRequestsController).Methods("GET")
	router.Handle("/main/api/arreservations/requests/history", controllers.GetARReservationsRequestsHistoryController).Methods("GET")
	//router.Handle("/main/api/reservations/requests/history/range", controllers.GetReservationsRequestsHistoryController).Methods("GET")
	router.Handle("/main/api/approve/arreservations/requests/{id}", controllers.UpdateARReservationController).Methods("PUT")

	// Destination Routes
	router.Handle("/main/api/town", controllers.CreateTownController).Methods("POST")
	router.Handle("/main/api/town", controllers.GetTownsController).Methods("GET")

	// Days Routes
	router.Handle("/main/api/day", controllers.CreateDayController).Methods("POST")
	router.Handle("/main/api/day", controllers.GetDaysController).Methods("GET")

	// Time Routes
	router.Handle("/main/api/time", controllers.CreateTimeController).Methods("POST")
	router.Handle("/main/api/time", controllers.GetTimesController).Methods("GET")

	// Destination with Time Routes
	/*router.Handle("/main/api/destination/time", controllers.CreateDestinationDayTimesController).Methods("POST")*/
	router.Handle("/main/api/destination/time", controllers.GetDestinationDayTimesController).Methods("GET")

	// Buses routes
	router.Handle("/main/api/buses/{id}", controllers.GetBusesController).Methods("GET")
	router.Handle("/main/api/available/buses/{id}", controllers.GetAvailableBusesController).Methods("GET")

	// Workflow Routes
	router.Handle("/main/api/workflow", controllers.GetModesController).Methods("GET")
	router.Handle("/main/api/workflow", controllers.CreateModeController).Methods("POST")
	router.Handle("/main/api/workflow/{id}", controllers.UpdateWorkFlowStatusController).Methods("PUT")

	// Penalty Due Times Routes
	router.Handle("/main/api/penalty/time", controllers.CreatePenaltyTimeController).Methods("POST")
	router.Handle("/main/api/penalty/time", controllers.GetPenaltyTimesController).Methods("GET")
	router.Handle("/main/api/latest/penalty/time", controllers.GetLatestPenaltyTimesController).Methods("GET")
	router.Handle("/main/api/penalty/time/{id}", controllers.UpdateDueTimeStatusController).Methods("PUT")
	router.Handle("/main/api/penalty/charge/{id}", controllers.GetPenaltyChargeController).Methods("GET")

	// Penalty Due Times Routes
	router.Handle("/main/api/penalty", controllers.CreatePenaltyController).Methods("POST")
	router.Handle("/main/api/penalty", controllers.GetPenaltiesController).Methods("GET")
	router.Handle("/main/api/latest/penalty", controllers.GetLatestPenaltyController).Methods("GET")
	router.Handle("/main/api/accumulated/penalties/{id}", controllers.GetAccumulatedPenaltiesController).Methods("GET")
	router.Handle("/main/api/accumulated/penalties/{id}/{status}", controllers.GetAccumulatedPenaltiesByStatusController).Methods("GET")

	// Penalties
	router.Handle("/api/penalty", controllers.CreatePenaltyController).Methods("POST")
	router.Handle("/api/penalty/{id}", controllers.UpdatePenaltyController).Methods("POST")
	router.Handle("/api/penalties/{id}", controllers.UpdatePenaltyController).Methods("POST")
	router.Handle("/api/penalty", controllers.GetPenaltiesController).Methods("GET")
	router.Handle("/api/latest/penalty", controllers.GetLatestPenaltyController).Methods("GET")
	router.Handle("/api/accumulated/penalties/{id}", controllers.GetAccumulatedPenaltiesController).Methods("GET")
	router.Handle("/api/accumulated/penalties/{id}/{status}", controllers.GetAccumulatedPenaltiesByStatusController).Methods("GET")

	// Notification Routes
	router.Handle("/main/api/email", controllers.GetEmailController).Methods("GET")
	router.Handle("/main/api/sms", controllers.GetSMSController).Methods("GET")

	// Options
	router.Handle("/main/api/options/get", controllers.GetOptionsController).Methods("GET")
	router.Handle("/main/api/options", controllers.GetOptionsController).Methods("GET")
	router.Handle("/main/api/options/{id}", controllers.GetOptionController).Methods("GET")
	router.Handle("/main/api/options/{id}", controllers.UpdateOptionController).Methods("PUT")

	// Charges
	router.Handle("/main/api/charges", controllers.GetChargesController).Methods("GET")

	// Destination routes
	router.Handle("/main/api/destinations", controllers.CreateBusRoutesController).Methods("POST")
	router.Handle("/main/api/destinations/{id}", controllers.UpdateBusRoutesController).Methods("PUT")
	router.Handle("/main/api/destinations", controllers.GetBusRoutesController).Methods("GET")
	router.Handle("/main/api/destinations/{code}", controllers.GetBusRoutesByCodeController).Methods("GET")
	router.Handle("/main/api/destinations/{code}/{date}", controllers.GetBusRoutesByCodeAndDateController).Methods("GET")
	router.Handle("/main/api/destinations/user-id/{user_id}", controllers.GetBusRoutesByUserIdController).Methods("GET")
	router.Handle("/main/api/destinations/route-id/{route_id}", controllers.GetBusRoutesByRouteIdController).Methods("GET")

	if err := http.ListenAndServe("0.0.0.0:7080", logs.LogRequest("", handlers.LoggingHandler(os.Stdout, router))); err != nil {
		panic(err)
	}

}

var loc *time.Location

func setTimezone(tz string) error {
	location, err := time.LoadLocation("Africa/Cairo")
	if err != nil {
		return err
	}
	loc = location
	return nil
}

func getTime(t time.Time) time.Time {
	return t.In(loc)
}
