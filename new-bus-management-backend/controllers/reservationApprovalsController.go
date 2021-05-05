package controllers

import (
	"bytes"
	"encoding/json"
	"fmt"
	"log"
	"net/http"
	"new-bus-management-backend/models"
	"new-bus-management-backend/utils"
	"os"

	"github.com/gorilla/mux"
)

// GetReservationsRequestsController Function retrieving Reservations requests Admin side
var GetReservationsRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetCurrentReservation()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// GetReservationsRequestsHistoryController blah blah
var GetReservationsRequestsHistoryController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetReservationHistory()
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
var UpdateReservationController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id := params["id"]
	reservation := &models.EdReservation{}

	err := json.NewDecoder(r.Body).Decode(reservation)
	if err != nil {
		utils.Respond(w, utils.Message(false, "Error while decoding request body"))
		return
	}

	resp := reservation.Update(id)
	if reservation.ReservationStatus == "C" {
		url := os.Getenv("probase_cancel_slot_url")
		fmt.Println("URL:>", url)
		var str = fmt.Sprintf(`{"payload":{"bus_no":"%d", "route_code":"%s", "schedule_id":"%d"}}`, reservation.BusId, reservation.EdBusRoute.RouteCode, reservation.ID)
		var jsonStr = []byte(str)
		req, _ := http.NewRequest("POST", url, bytes.NewBuffer(jsonStr))
		req.Header.Set("Content-Type", "application/json")

		client := &http.Client{}
		response, err := client.Do(req)

		if err == nil {
			resp := utils.Message(false, "success")
			resp["data"] = err
		}

		defer response.Body.Close()
	}
	utils.Respond(w, resp)
})

// CloseReservationController Function for closing slot
var CloseReservationController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	slot := &models.EdSlot{}

	err := json.NewDecoder(r.Body).Decode(slot)
	if err != nil {
		utils.Respond(w, utils.Message(false, "Error while decoding request body"))
		return
	}

	resp := slot.Close()
	utils.Respond(w, resp)

})
