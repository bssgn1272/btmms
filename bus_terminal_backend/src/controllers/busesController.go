package controllers

import (
	"log"
	"net/http"

	"../models"
	u "../utils"
	"github.com/gorilla/mux"
)

// GetBusesController Function for retrieving days requests for the day
var GetBusesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	id, _ := params["id"]

	data := models.GetBuses(id)
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})
