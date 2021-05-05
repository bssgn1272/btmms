package controllers

import (
	"encoding/json"
	"fmt"
	"log"
	"net/http"
	"new-bus-management-backend/models"
	"new-bus-management-backend/utils"
	"strconv"

	"github.com/gorilla/mux"
)

// CreateTimeController Function for time request function
var CreateBusRoutesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	route := &models.EdBusRoute{}

	err := json.NewDecoder(r.Body).Decode(route)
	if err != nil {
		utils.Respond(w, utils.Message(false, "Error while decoding request body"))
		return
	}

	resp := route.Create()
	utils.Respond(w, resp)
})

var UpdateBusRoutesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id, _ := strconv.Atoi(params["id"])

	route := &models.EdBusRoute{}

	err := json.NewDecoder(r.Body).Decode(route)
	if err != nil {
		utils.Respond(w, utils.Message(false, "Error while decoding request body"))
		return
	}

	resp := route.Update(uint(id))

	fmt.Println("CHECKEKEKEKE>>>>>>>", resp)

	//if err != nil {
	//
	//	w.WriteHeader(http.StatusInternalServerError)
	//	return
	//}
	//resp := utils.Message(true, "success")
	//resp["travel_routes"] = res
	utils.Respond(w, resp)
})

// GetTimesController Function for retrieving time requests for the day
var GetBusRoutesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetRoutes()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// GetTimesController Function for retrieving time requests for the day
var GetBusRoutesByCodeController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	code, _ := params["code"]

	fmt.Print(code)
	//if err != nil {
	//	//The passed path parameter is not an integer
	//	utils.Respond(w, utils.Message(false, "There was an error in your request"))
	//	return
	//}

	data := models.GetRouteByCode(code)
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// GetTimesController Function for retrieving time requests for the day
var GetBusRoutesByCodeAndDateController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	code, _ := params["code"]
	date, _ := params["date"]

	data := models.GetRouteByCodeAndDate(code, date)
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

var GetBusRoutesByUserIdController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id, err := strconv.Atoi(params["user_id"])

	if err != nil {
		//The passed path parameter is not an integer
		utils.Respond(w, utils.Message(false, "There was an error in your request"))
		return
	}

	data := models.GetRoutesByUserId(uint(id))
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// GetTimesController Function for retrieving time requests for the day
var GetBusRoutesByRouteIdController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	code, _ := strconv.Atoi(params["route_id"])

	fmt.Print(code)
	//if err != nil {
	//	//The passed path parameter is not an integer
	//	utils.Respond(w, utils.Message(false, "There was an error in your request"))
	//	return
	//}

	route := models.EdReservation{}
	data := route.GetRouteByRouteID(uint(code))
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})
