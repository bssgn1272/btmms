
package main

import (
	c "./src/controllers"
	"./src/logs"
	"github.com/gorilla/handlers"
	"github.com/gorilla/mux"
	"log"
	"net/http"
	"os"
)

func main() {
	router := mux.NewRouter().StrictSlash(true)
	// router.Use(a.JwtAuthentication)
	f, err := os.OpenFile("" +
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


	// authentication routes
	router.Handle("/api/user/register", c.CreateUserController).Methods("POST")
	router.Handle("/api/login", c.AuthenticateUserController).Methods("POST")


	// reservations routes
	router.Handle("/api/reservation/requests/create", c.CreateReservationController).Methods("POST")
	router.Handle("/api/reservation/get", c.GetReservationsController).Methods("GET")
	router.Handle("/api/reservation/get/{id}", c.GetReservationsForController).Methods("GET")

	// Slots routes
	router.Handle("/api/slots/create", c.CreateSlotController).Methods("POST")
	router.Handle("/api/slots/get", c.GetSlotsController).Methods("GET")


	// Reservations Approval routes
	router.Handle("/api/reservations/requests", c.GetReservationsRequestsController).Methods("GET")
	router.Handle("/api/approve/reservations/requests/{id}", c.UpdateReservationController).Methods("PUT")
	router.Handle("/api/slots/close", c.CloseReservationController).Methods("PUT")


	// Slot One Requests
	router.Handle("/api/reservations/requests/slot_one/five", c.GetSlotOneFiveRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_one/six", c.GetSlotOneSixRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_one/seven", c.GetSlotOneSevenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_one/eight", c.GetSlotOneEightRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_one/nine", c.GetSlotOneNineRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_one/ten", c.GetSlotOneTenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_one/eleven", c.GetSlotOneElevenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_one/twelve", c.GetSlotOneTwelveRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_one/thirteen", c.GetSlotOneThirteenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_one/fourteen", c.GetSlotOneFourteenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_one/fifteen", c.GetSlotOneFifteenRequestsController).Methods("GET")


	// Slot Two Requests
	router.Handle("/api/reservations/requests/slot_two/five", c.GetSlotTwoFiveRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_two/six", c.GetSlotTwoSixRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_two/seven", c.GetSlotTwoSevenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_two/eight", c.GetSlotTwoEightRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_two/nine", c.GetSlotTwoNineRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_two/ten", c.GetSlotTwoTenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_two/eleven", c.GetSlotTwoElevenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_two/twelve", c.GetSlotTwoTwelveRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_two/thirteen", c.GetSlotTwoThirteenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_two/fourteen", c.GetSlotTwoFourteenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_two/fifteen", c.GetSlotTwoFifteenRequestsController).Methods("GET")


	// Slot Three requests

	router.Handle("/api/reservations/requests/slot_three/five", c.GetSlotThreeFiveRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_three/six", c.GetSlotThreeSixRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_three/seven", c.GetSlotThreeSevenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_three/eight", c.GetSlotThreeEightRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_three/nine", c.GetSlotThreeNineRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_three/ten", c.GetSlotThreeTenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_three/eleven", c.GetSlotThreeElevenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_three/twelve", c.GetSlotThreeTwelveRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_three/thirteen", c.GetSlotThreeThirteenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_three/fourteen", c.GetSlotThreeFourteenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_three/fifteen", c.GetSlotThreeFifteenRequestsController).Methods("GET")


	// Slot Four Requests

	router.Handle("/api/reservations/requests/slot_four/five", c.GetSlotFourFiveRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_four/six", c.GetSlotFourSixRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_four/seven", c.GetSlotFourSevenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_four/eight", c.GetSlotFourEightRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_four/nine", c.GetSlotFourNineRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_four/ten", c.GetSlotFourTenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_four/eleven", c.GetSlotFourElevenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_four/twelve", c.GetSlotFourTwelveRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_four/thirteen", c.GetSlotFourThirteenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_four/fourteen", c.GetSlotFourFourteenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_four/fifteen", c.GetSlotFourFifteenRequestsController).Methods("GET")


	// Slot Five Requests

	router.Handle("/api/reservations/requests/slot_five/five", c.GetSlotFiveFiveRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_five/six", c.GetSlotFiveSixRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_five/seven", c.GetSlotFiveSevenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_five/eight", c.GetSlotFiveEightRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_five/nine", c.GetSlotFiveNineRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_five/ten", c.GetSlotFiveTenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_five/eleven", c.GetSlotFiveElevenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_five/twelve", c.GetSlotFiveTwelveRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_five/thirteen", c.GetSlotFiveThirteenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_five/fourteen", c.GetSlotFiveFourteenRequestsController).Methods("GET")
	router.Handle("/api/reservations/requests/slot_five/fifteen", c.GetSlotFiveFifteenRequestsController).Methods("GET")






	if err := http.ListenAndServe("0.0.0.0:7080", logs.LogRequest("",handlers.LoggingHandler(os.Stdout, router))); err != nil {
		panic(err)
	}

}
