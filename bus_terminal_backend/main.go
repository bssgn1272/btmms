package main

import (
	"fmt"
	"github.com/gorilla/handlers"
	"github.com/gorilla/mux"
	"log"
	"net/http"
	"new-bus-management-backend/controllers"
	"new-bus-management-backend/logs"
	"os"
	"time"

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
	router.Handle("/api/user/register", controllers.CreateUserController).Methods("POST")
	router.Handle("/api/login", controllers.AuthenticateUserController).Methods("POST")
	router.Handle("/api/auth/changepassword", controllers.ChangePassword).Methods("POST")
	router.Handle("/api/auth/resetpassword", controllers.ResetPassword).Methods("POST")

	// reservations routes
	router.Handle("/api/reservation/requests/create", controllers.CreateReservationController).Methods("POST")
	router.Handle("/api/reservation/get", controllers.GetReservationsController).Methods("GET")
	router.Handle("/api/reservation/get/{id}", controllers.GetReservationsForController).Methods("GET")
	router.Handle("/api/reservation/history/get/{id}", controllers.GetReservationsHistoryForController).Methods("GET")

	// arrival reservations routes
	router.Handle("/api/arreservation/requests/create", controllers.ArCreateReservationController).Methods("POST")
	router.Handle("/api/arreservation/get", controllers.ArGetReservationsController).Methods("GET")
	router.Handle("/api/arreservation/get/{id}", controllers.ArGetReservationsForController).Methods("GET")
	router.Handle("/api/arreservation/history/get/{id}", controllers.ArGetReservationsHistoryForController).Methods("GET")

	// Slots routes
	router.Handle("/api/slots/create", controllers.CreateSlotController).Methods("POST")
	router.Handle("/api/slots/get", controllers.GetSlotsController).Methods("GET")
	router.Handle("/api/slots/getbydate/{date}", controllers.GetSlotsByDateController).Methods("GET")
	router.Handle("/api/slots/update/{id}", controllers.UpdateSlotController).Methods("PUT")
	router.Handle("/api/slots/charge/{id}", controllers.GetLoadingFeeController).Methods("GET")

	// Arrival Slots routes
	router.Handle("/api/arslots/create", controllers.ArCreateSlotController).Methods("POST")
	router.Handle("/api/arslots/get", controllers.ArGetSlotsController).Methods("GET")
	router.Handle("/api/arslots/getbydate/{date}", controllers.ArGetSlotsByDateController).Methods("GET")
	router.Handle("/api/arslots/update/{id}", controllers.ArUpdateSlotController).Methods("PUT")

	// Reservations Approval routes
	router.Handle("/api/reservations/requests", controllers.GetReservationsRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/history", controllers.GetReservationsRequestsHistoryController).Methods("GET")
	//router.Handle("/api/reservations/requests/history/range", controllers.GetReservationsRequestsHistoryController).Methods("GET")
	router.Handle("/api/approve/reservations/requests/{id}", controllers.UpdateReservationController).Methods("PUT")
	router.Handle("/api/slots/close", controllers.CloseReservationController).Methods("PUT")


	// Arrival Reservations Approval routes
	router.Handle("/api/arreservations/requests", controllers.GetARReservationsRequestsController).Methods("GET")
	router.Handle("/api/arreservations/requests/history", controllers.GetARReservationsRequestsHistoryController).Methods("GET")
	//router.Handle("/api/reservations/requests/history/range", controllers.GetReservationsRequestsHistoryController).Methods("GET")
	router.Handle("/api/approve/arreservations/requests/{id}", controllers.UpdateARReservationController).Methods("PUT")

	// Destination Routes
	router.Handle("/api/town", controllers.CreateTownController).Methods("POST")
	router.Handle("/api/town", controllers.GetTownsController).Methods("GET")

	// Days Routes
	router.Handle("/api/day", controllers.CreateDayController).Methods("POST")
	router.Handle("/api/day", controllers.GetDaysController).Methods("GET")

	// Time Routes
	router.Handle("/api/time", controllers.CreateTimeController).Methods("POST")
	router.Handle("/api/time", controllers.GetTimesController).Methods("GET")

	// Destination with Time Routes
	/*router.Handle("/api/destination/time", controllers.CreateDestinationDayTimesController).Methods("POST")*/
	router.Handle("/api/destination/time", controllers.GetDestinationDayTimesController).Methods("GET")

	// Buses routes
	router.Handle("/api/buses/{id}", controllers.GetBusesController).Methods("GET")
	router.Handle("/api/available/buses/{id}", controllers.GetAvailableBusesController).Methods("GET")

	// Workflow Routes
	router.Handle("/api/workflow", controllers.GetModesController).Methods("GET")
	router.Handle("/api/workflow", controllers.CreateModeController).Methods("POST")
	router.Handle("/api/workflow/{id}", controllers.UpdateWorkFlowStatusController).Methods("PUT")

	// Penalty Due Times Routes
	router.Handle("/api/penalty/time", controllers.CreatePenaltyTimeController).Methods("POST")
	router.Handle("/api/penalty/time", controllers.GetPenaltyTimesController).Methods("GET")
	router.Handle("/api/latest/penalty/time", controllers.GetLatestPenaltyTimesController).Methods("GET")
	router.Handle("/api/penalty/time/{id}", controllers.UpdateDueTimeStatusController).Methods("PUT")
	router.Handle("/api/penalty/charge/{id}", controllers.GetPenaltyChargeController).Methods("GET")

	// Penalty Due Times Routes
	router.Handle("/api/penalty", controllers.CreatePenaltyController).Methods("POST")
	router.Handle("/api/penalty", controllers.GetPenaltiesController).Methods("GET")
	router.Handle("/api/latest/penalty", controllers.GetLatestPenaltyController).Methods("GET")
	router.Handle("/api/accumulated/penalties/{id}", controllers.GetAccumulatedPenaltiesController).Methods("GET")

	// Notification Routes
	router.Handle("/api/email", controllers.GetEmailController).Methods("GET")
	router.Handle("/api/sms", controllers.GetSMSController).Methods("GET")

	// Options
	router.Handle("/api/options/get", controllers.GetOptionsController).Methods("GET")

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

