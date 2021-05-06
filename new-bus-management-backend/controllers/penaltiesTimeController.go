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

// CreatePenaltyTimeController Function for time request function
var CreatePenaltyTimeController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	time := &models.EdPenaltyInterval{}

	err := json.NewDecoder(r.Body).Decode(time)
	if err != nil {
		utils.Respond(w, utils.Message(false, "Error while decoding request body"))
		return
	}

	resp := time.CreatePenaltyTime()
	utils.Respond(w, resp)
})

// GetPenaltyTimesController Function for retrieving time requests for the day
var GetPenaltyTimesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetPenaltyTimes()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// GetLatestPenaltyTimesController Function for retrieving time requests for the day
var GetLatestPenaltyTimesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetLatestPenaltyTimes()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// UpdateDueTimeStatusController Function for updating Mode status
var UpdateDueTimeStatusController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id, _ := strconv.Atoi(params["id"])
	workFlow := &models.EdPenaltyInterval{}

	err := json.NewDecoder(r.Body).Decode(workFlow)
	if err != nil {
		utils.Respond(w, utils.Message(false, "Error while decoding request body"))
		return
	}

	resp := workFlow.UpdateDueTimeStatus(uint(id))
	utils.Respond(w, resp)

})
