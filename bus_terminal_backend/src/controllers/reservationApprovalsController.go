package controllers

import (
	"../models"
	u "../utils"
	"log"
	"net/http"
)

var GetReservationsRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetCurrentReservation()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

