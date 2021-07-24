package controllers

import (
	"encoding/json"
	"fmt"
	"log"
	"net/http"
	"new-bus-management-backend/models"
	"new-bus-management-backend/utils"

	"github.com/gorilla/mux"
)

// GetReservationsRequestsController Function retrieving Reservations requests Admin side
var GetARReservationsRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.ArGetCurrentReservation()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// GetReservationsRequestsController Function retrieving Reservations requests Admin side
var GetARReservationsRequestsPendingController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.ArGetCurrentPendingReservation()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// GetReservationsRequestsHistoryController blah blah
var GetARReservationsRequestsHistoryController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.ArGetReservationHistory()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// Function retrieving Reservations requests Admin side based on range
//var GetReservationsRequestsHistoryController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
//
//	w.Header().Set("content-type", "application/json")
//	queryValues := r.URL.Query()
//	_, _ = fmt.Fprintf(w, "hello, %s!\n", queryValues.Get("fromDate"))
//
//	fromDate, _ := time.Parse(time.RFC3339, queryValues.Get("fromDate"))
//
//	toDate, _ := time.Parse(time.RFC3339, queryValues.Get("toDate"))
//
//	data := models.GetReservationsHistory(time.Time(fromDate), time.Time(toDate))
//	resp := utils.Message(true, "success")
//	resp["data"] = data
//	log.Println(resp)
//	utils.Respond(w, resp)
//})

// UpdateReservationController Function for Approving reservations requests
var UpdateARReservationController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id := params["id"]
	reservation := &models.EdArReservation{}

	err := json.NewDecoder(r.Body).Decode(reservation)
	if err != nil {
		utils.Respond(w, utils.Message(false, "Error while decoding request body"))
		return
	}
	fmt.Print("Reservation Cancellation: ")
	resp := reservation.ARUpdate(id)
	utils.Respond(w, resp)
})

// CloseReservationController Function for closing slot
var CloseARReservationController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	slot := &models.EdArSlot{}

	err := json.NewDecoder(r.Body).Decode(slot)
	if err != nil {
		utils.Respond(w, utils.Message(false, "Error while decoding request body"))
		return
	}

	resp := slot.Close()
	utils.Respond(w, resp)

})
