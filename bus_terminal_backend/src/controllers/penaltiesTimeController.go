package controllers

import (
	"encoding/json"
	"log"
	"net/http"
	"strconv"

	"../models"
	u "../utils"
	"github.com/gorilla/mux"
)

// CreatePenaltyTimeController Function for time request function
var CreatePenaltyTimeController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	time := &models.EdPenaltyInterval{}

	err := json.NewDecoder(r.Body).Decode(time)
	if err != nil {
		u.Respond(w, u.Message(false, "Error while decoding request body"))
		return
	}

	resp := time.CreatePenaltyTime()
	u.Respond(w, resp)
})

// GetPenaltyTimesController Function for retrieving time requests for the day
var GetPenaltyTimesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetPenaltyTimes()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

// GetLatestPenaltyTimesController Function for retrieving time requests for the day
var GetLatestPenaltyTimesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetLatestPenaltyTimes()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

// UpdateDueTimeStatusController Function for updating Mode status
var UpdateDueTimeStatusController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id, err := strconv.Atoi(params["id"])
	workFlow := &models.EdPenaltyInterval{}

	err = json.NewDecoder(r.Body).Decode(workFlow)
	if err != nil {
		u.Respond(w, u.Message(false, "Error while decoding request body"))
		return
	}

	resp := workFlow.UpdateDueTimeStatus(uint(id))
	u.Respond(w, resp)

})
