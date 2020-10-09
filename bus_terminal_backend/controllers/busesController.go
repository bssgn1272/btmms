package controllers

import (
	"log"
	"net/http"
	"new-bus-management-backend/models"
	"new-bus-management-backend/utils"

	"github.com/gorilla/mux"
)

// GetBusesController Function for retrieving buses by operator ID
var GetBusesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	id, _ := params["id"]

	data := models.GetBuses(id)
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// GetAvailableBusesController Function for retrieving available buses by operator ID
var GetAvailableBusesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	id, _ := params["id"]

	data := models.GetAvailableBuses(id)
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})
