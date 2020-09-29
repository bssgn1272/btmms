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

// CreatePenaltyController Function for time request function
var CreatePenaltyController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	penalty := &models.EdPenalty{}

	err := json.NewDecoder(r.Body).Decode(penalty)
	if err != nil {
		utils.Respond(w, utils.Message(false, "Error while decoding request body"))
		return
	}

	resp := penalty.CreatePenalty()
	utils.Respond(w, resp)
})

// GetPenaltiesController Function for retrieving penalty requests for the day
var GetPenaltiesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetPenalties()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// GetLatestPenaltyController Function for retrieving time requests for the day
var GetLatestPenaltyController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetLatestPenalty()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// GetPenaltyChargeController get charge for bus' late cancellation
var GetPenaltyChargeController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	id := params["id"]
	data := models.GetPenaltyCharge(id)
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// GetLoadingFeeController get charge for bus' late cancellation
var GetLoadingFeeController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	id := params["id"]
	data := models.GetLoadingFee(id)
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})




var GetAccumulatedPenaltiesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	id, _ := strconv.Atoi(params["id"])
	data := models.GetAccumulatedPenalties(uint(id))
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})
