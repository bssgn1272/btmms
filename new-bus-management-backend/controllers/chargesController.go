package controllers

import (
	"log"
	"net/http"
	"new-bus-management-backend/models"
	"new-bus-management-backend/utils"
)

// GetOptionsController Function for retrieving Options
var GetChargesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetCharges()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})
