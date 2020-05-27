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

// Function for time request function
var CreateModeController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {


	mode := &models.EdWorkFlow{}

	err := json.NewDecoder(r.Body).Decode(mode)
	if err != nil {
		u.Respond(w, u.Message(false, "Error while decoding request body"))
		return
	}

	resp := mode.Create()
	u.Respond(w, resp)
})

// Function for retrieving mode requests for the day
var GetModesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetModes()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})


// Function for updating Mode status
var UpdateWorkFlowStatusController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id, err := strconv.Atoi(params["id"])
	workFlow := &models.EdWorkFlow{}

	err = json.NewDecoder(r.Body).Decode(workFlow)
	if err != nil {
		u.Respond(w, u.Message(false, "Error while decoding request body"))
		return
	}

	resp := workFlow.UpdateModeStatus(uint(id))
	u.Respond(w, resp)

})