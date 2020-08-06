package controllers

import (
	"encoding/json"
	"fmt"
	"log"
	"net/http"

	"../models"
	u "../utils"
	"github.com/gorilla/mux"
)

// GetReservationsRequestsController Function retrieving Reservations requests Admin side
var GetReservationsRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetCurrentReservation()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

// GetReservationsRequestsHistoryController blah blah
var GetReservationsRequestsHistoryController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetReservationHistory()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
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
//	resp := u.Message(true, "success")
//	resp["data"] = data
//	log.Println(resp)
//	u.Respond(w, resp)
//})

// UpdateReservationController Function for Approving reservations requests
var UpdateReservationController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id := params["id"]
	reservation := &models.EdReservation{}

	err := json.NewDecoder(r.Body).Decode(reservation)
	if err != nil {
		u.Respond(w, u.Message(false, "Error while decoding request body"))
		return
	}
	fmt.Print("Reservation Cancellation: ")
	fmt.Println(reservation.CancellationReason)
	resp := reservation.Update(id)
	u.Respond(w, resp)
})

// CloseReservationController Function for closing slot
var CloseReservationController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	slot := &models.EdSlot{}

	err := json.NewDecoder(r.Body).Decode(slot)
	if err != nil {
		u.Respond(w, u.Message(false, "Error while decoding request body"))
		return
	}

	resp := slot.Close()
	u.Respond(w, resp)

})
