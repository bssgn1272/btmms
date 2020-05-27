package controllers

import (
	"../models"
	u "../utils"
	"encoding/json"
	"fmt"
	"github.com/gorilla/mux"
	"log"
	"net/http"
	"strconv"
)

// Function for reservation request function
var CreateReservationController http.HandlerFunc = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {


	reservation := &models.EdReservation{}

	err := json.NewDecoder(r.Body).Decode(reservation)
	if err != nil {
		u.Respond(w, u.Message(false, "Error while decoding request body"))
		w.WriteHeader(http.StatusBadRequest)
		return
	}

	resp := reservation.Create()
	_ = json.NewEncoder(w).Encode(resp)
})

// Function for retrieving reservation requests for the day
var GetReservationsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetReservations()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

// Function for retrieving reservations for a particular user
var GetReservationsForController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id, err := strconv.Atoi(params["id"])

	fmt.Print(id)
	if err != nil {
		//The passed path parameter is not an integer
		u.Respond(w, u.Message(false, "There was an error in your request"))
		return
	}

	data := models.GetReservation(uint(id))
	resp := u.Message(true, "success")
	resp["data"] = data
	u.Respond(w, resp)
})


// Function for retrieving reservations for a particular user
var GetReservationsHistoryForController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id, err := strconv.Atoi(params["id"])

	fmt.Print(id)
	if err != nil {
		//The passed path parameter is not an integer
		u.Respond(w, u.Message(false, "There was an error in your request"))
		return
	}

	data := models.GetReservationOperatorHistory(uint(id))
	resp := u.Message(true, "success")
	resp["data"] = data
	u.Respond(w, resp)
})
