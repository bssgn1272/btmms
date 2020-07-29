package controllers

import (
	"encoding/json"
	"fmt"
	"log"
	"net/http"
	"strconv"

	"../models"
	u "../utils"
	"github.com/gorilla/mux"
)

// ArCreateReservationController Function for reservation request function
var ArCreateReservationController http.HandlerFunc = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	reservation := &models.EdArReservation{}

	err := json.NewDecoder(r.Body).Decode(reservation)
	if err != nil {
		u.Respond(w, u.Message(false, err.Error() /*"Error while decoding request body"*/))
		w.WriteHeader(http.StatusBadRequest)
		return
	}

	resp := reservation.ArCreate()
	_ = json.NewEncoder(w).Encode(resp)
})

// ArGetReservationsController Function for retrieving reservation requests for the day
var ArGetReservationsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.ArGetReservations()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

// ArGetReservationsForController Function for retrieving reservations for a particular user
var ArGetReservationsForController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id, err := strconv.Atoi(params["id"])

	fmt.Print(id)
	if err != nil {
		//The passed path parameter is not an integer
		u.Respond(w, u.Message(false, "There was an error in your request"))
		return
	}

	data := models.ArGetReservation(uint(id))
	resp := u.Message(true, "success")
	resp["data"] = data
	u.Respond(w, resp)
})

// ArGetReservationsHistoryForController Function for retrieving reservations for a particular user
var ArGetReservationsHistoryForController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id, err := strconv.Atoi(params["id"])

	fmt.Print(id)
	if err != nil {
		//The passed path parameter is not an integer
		u.Respond(w, u.Message(false, "There was an error in your request"))
		return
	}

	data := models.ArGetReservationOperatorHistory(uint(id))
	resp := u.Message(true, "success")
	resp["data"] = data
	u.Respond(w, resp)
})
