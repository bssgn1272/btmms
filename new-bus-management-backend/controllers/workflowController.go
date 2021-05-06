package controllers

import (
	"encoding/json"
	"log"
	"net/http"
	"new-bus-management-backend/models"
	"new-bus-management-backend/utils"
	"strconv"

	"github.com/gorilla/mux"
)

// CreateModeController Function for time request function
var CreateModeController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	mode := &models.EdWorkFlow{}

	err := json.NewDecoder(r.Body).Decode(mode)
	if err != nil {
		utils.Respond(w, utils.Message(false, "Error while decoding request body"))
		return
	}

	resp := mode.Create()
	utils.Respond(w, resp)
})

// GetModesController Function for retrieving mode requests for the day
var GetModesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetModes()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// UpdateWorkFlowStatusController Function for updating Mode status
var UpdateWorkFlowStatusController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id, _ := strconv.Atoi(params["id"])
	workFlow := &models.EdWorkFlow{}

	err := json.NewDecoder(r.Body).Decode(workFlow)
	if err != nil {
		utils.Respond(w, utils.Message(false, "Error while decoding request body"))
		return
	}

	resp := workFlow.UpdateModeStatus(uint(id))
	utils.Respond(w, resp)

})
