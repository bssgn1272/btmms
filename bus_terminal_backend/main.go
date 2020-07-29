package main

import (
	"fmt"
	"log"
	"net/http"
	"os"
	"time"

	c "./src/controllers"
	"./src/logs"
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
	go c.InitMidNight()
	go c.RunAccessControl()
	go c.RunSMSNotifications()

	// authentication routes
	router.Handle("/api/user/register", c.CreateUserController).Methods("POST")
	router.Handle("/api/login", c.AuthenticateUserController).Methods("POST")
	router.Handle("/api/auth/changepassword", c.ChangePassword).Methods("POST")
	router.Handle("/api/auth/resetpassword", c.ResetPassword).Methods("POST")

	// reservations routes
	router.Handle("/api/reservation/requests/create", c.CreateReservationController).Methods("POST")
	router.Handle("/api/reservation/get", c.GetReservationsController).Methods("GET")
	router.Handle("/api/reservation/get/{id}", c.GetReservationsForController).Methods("GET")
	router.Handle("/api/reservation/history/get/{id}", c.GetReservationsHistoryForController).Methods("GET")

	// arrival reservations routes
	router.Handle("/api/arreservation/requests/create", c.ArCreateReservationController).Methods("POST")
	router.Handle("/api/arreservation/get", c.ArGetReservationsController).Methods("GET")
	router.Handle("/api/arreservation/get/{id}", c.ArGetReservationsForController).Methods("GET")
	router.Handle("/api/arreservation/history/get/{id}", c.ArGetReservationsHistoryForController).Methods("GET")

	// Slots routes
	router.Handle("/api/slots/create", c.CreateSlotController).Methods("POST")
	router.Handle("/api/slots/get", c.GetSlotsController).Methods("GET")
	router.Handle("/api/slots/getbydate/{date}", c.GetSlotsByDateController).Methods("GET")
	router.Handle("/api/slots/update/{id}", c.UpdateSlotController).Methods("PUT")
	router.Handle("/api/slots/charge/{id}", c.GetLoadingFeeController).Methods("GET")

	// Arrival Slots routes
	router.Handle("/api/arslots/create", c.ArCreateSlotController).Methods("POST")
	router.Handle("/api/arslots/get", c.ArGetSlotsController).Methods("GET")
	router.Handle("/api/arslots/getbydate/{date}", c.ArGetSlotsByDateController).Methods("GET")
	router.Handle("/api/arslots/update/{id}", c.ArUpdateSlotController).Methods("PUT")

	// Reservations Approval routes
	router.Handle("/api/reservations/requests", c.GetReservationsRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/history", c.GetReservationsRequestsHistoryController).Methods("GET")
	//router.Handle("/api/reservations/requests/history/range", c.GetReservationsRequestsHistoryController).Methods("GET")
	router.Handle("/api/approve/reservations/requests/{id}", c.UpdateReservationController).Methods("PUT")
	router.Handle("/api/slots/close", c.CloseReservationController).Methods("PUT")

	// Destination Routes
	router.Handle("/api/town", c.CreateTownController).Methods("POST")
	router.Handle("/api/town", c.GetTownsController).Methods("GET")

	// Days Routes
	router.Handle("/api/day", c.CreateDayController).Methods("POST")
	router.Handle("/api/day", c.GetDaysController).Methods("GET")

	// Time Routes
	router.Handle("/api/time", c.CreateTimeController).Methods("POST")
	router.Handle("/api/time", c.GetTimesController).Methods("GET")

	// Destination with Time Routes
	/*router.Handle("/api/destination/time", c.CreateDestinationDayTimesController).Methods("POST")*/
	router.Handle("/api/destination/time", c.GetDestinationDayTimesController).Methods("GET")

	// Buses routes
	router.Handle("/api/buses/{id}", c.GetBusesController).Methods("GET")

	// Workflow Routes
	router.Handle("/api/workflow", c.GetModesController).Methods("GET")
	router.Handle("/api/workflow", c.CreateModeController).Methods("POST")
	router.Handle("/api/workflow/{id}", c.UpdateWorkFlowStatusController).Methods("PUT")

	// Penalty Due Times Routes
	router.Handle("/api/penalty/time", c.CreatePenaltyTimeController).Methods("POST")
	router.Handle("/api/penalty/time", c.GetPenaltyTimesController).Methods("GET")
	router.Handle("/api/latest/penalty/time", c.GetLatestPenaltyTimesController).Methods("GET")
	router.Handle("/api/penalty/time/{id}", c.UpdateDueTimeStatusController).Methods("PUT")
	router.Handle("/api/penalty/charge/{id}", c.GetPenaltyChargeController).Methods("GET")

	// Penalty Due Times Routes
	router.Handle("/api/penalty", c.CreatePenaltyController).Methods("POST")
	router.Handle("/api/penalty", c.GetPenaltiesController).Methods("GET")
	router.Handle("/api/latest/penalty", c.GetLatestPenaltyController).Methods("GET")

	// Notification Routes
	router.Handle("/api/email", c.GetEmailController).Methods("GET")
	router.Handle("/api/sms", c.GetSMSController).Methods("GET")

	// Options
	router.Handle("/api/options/get", c.GetOptionsController).Methods("GET")

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
