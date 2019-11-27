package controllers

import (
	"../models"
	u "../utils"
	"encoding/json"
	"github.com/gorilla/mux"
	"log"
	"net/http"
	"strconv"
)

var CreateReservationController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	user := r.Context().Value("user") . (uint) //Grab the id of the user that send the request
	reservation := &models.Reservation{}

	err := json.NewDecoder(r.Body).Decode(reservation)
	if err != nil {
		u.Respond(w, u.Message(false, "Error while decoding request body"))
		return
	}

	reservation.UserId = user
	resp := reservation.Create()
	u.Respond(w, resp)
})

var GetReservationsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetReservations()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})


var GetReservationsForController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id, err := strconv.Atoi(params["id"])
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
