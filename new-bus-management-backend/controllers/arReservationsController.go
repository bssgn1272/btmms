package controllers

import (
	"encoding/json"
	"fmt"
	"log"
	"net/http"
	"new-bus-management-backend/models"
	"new-bus-management-backend/utils"
	"strconv"

	"github.com/gorilla/mux"
)

// ArCreateReservationController Function for reservation request function
var ArCreateReservationController http.HandlerFunc = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	reservation := &models.EdArReservation{}

	err := json.NewDecoder(r.Body).Decode(reservation)
	if err != nil {
		fmt.Print(err)
		utils.Respond(w, utils.Message(false, err.Error() /*"Error while decoding request body"*/))
		w.WriteHeader(http.StatusBadRequest)
		return
	}

	resp := reservation.ArCreate()
	_ = json.NewEncoder(w).Encode(resp)
})

// ArGetReservationsController Function for retrieving reservation requests for the day
var ArGetReservationsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.ArGetReservations()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

var ArGetActiveReservationsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.ArGetActiveReservations()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// ArGetReservationsForController Function for retrieving reservations for a particular user
var ArGetReservationsForController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id, err := strconv.Atoi(params["id"])

	fmt.Print(id)
	if err != nil {
		//The passed path parameter is not an integer
		utils.Respond(w, utils.Message(false, "There was an error in your request"))
		return
	}

	data := models.ArGetReservation(uint(id))
	resp := utils.Message(true, "success")
	resp["data"] = data
	utils.Respond(w, resp)
})

// ArGetReservationsHistoryForController Function for retrieving reservations for a particular user
var ArGetReservationsHistoryForController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id, err := strconv.Atoi(params["id"])

	fmt.Print(id)
	if err != nil {
		//The passed path parameter is not an integer
		utils.Respond(w, utils.Message(false, "There was an error in your request"))
		return
	}

	data := models.ArGetReservationOperatorHistory(uint(id))
	resp := utils.Message(true, "success")
	resp["data"] = data
	utils.Respond(w, resp)
})
