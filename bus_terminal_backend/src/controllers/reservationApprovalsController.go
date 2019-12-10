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

var GetReservationsRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetCurrentReservation()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})


var UpdateReservationController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id, err := strconv.Atoi(params["id"])
	reservation := &models.Reservation{}

	err = json.NewDecoder(r.Body).Decode(reservation)
	if err != nil {
		u.Respond(w, u.Message(false, "Error while decoding request body"))
		return
	}

	resp := reservation.Update(uint(id))
	u.Respond(w, resp)

})

var CloseReservationController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	slot :=&models.Slot{}

	err := json.NewDecoder(r.Body).Decode(slot)
	if err != nil {
		u.Respond(w, u.Message(false, "Error while decoding request body"))
		return
	}

	resp := slot.Close()
	u.Respond(w, resp)

})

// Get slot requests
var GetSlotOneFiveRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotOneFive()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotOneSixRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotOneSix()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotOneSevenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotOneSeven()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotOneEightRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotOneEight()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotOneNineRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotOneNine()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotOneTenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotOneTen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotOneElevenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotOneEleven()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotOneTwelveRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotOneTwelve()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotOneThirteenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotOneThirteen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotOneFourteenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotOneFourteen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotOneFifteenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotOneFifteen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})


// Get slot requests
var GetSlotTwoFiveRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotTwoFive()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotTwoSixRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotTwoSix()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotTwoSevenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotTwoSeven()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotTwoEightRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotTwoEight()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotTwoNineRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotTwoNine()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotTwoTenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotTwoTen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotTwoElevenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotTwoEleven()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotTwoTwelveRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotTwoTwelve()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotTwoThirteenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotTwoThirteen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotTwoFourteenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotTwoFourteen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotTwoFifteenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotTwoFifteen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})


// Get slot requests
var GetSlotThreeFiveRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotThreeFive()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotThreeSixRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotThreeSix()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotThreeSevenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotThreeSeven()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotThreeEightRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotThreeEight()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotThreeNineRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotThreeNine()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotThreeTenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotThreeTen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotThreeElevenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotThreeEleven()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotThreeTwelveRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotThreeTwelve()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotThreeThirteenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotThreeThirteen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotThreeFourteenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotThreeFourteen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotThreeFifteenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotThreeFifteen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})


// Get slot requests
var GetSlotFourFiveRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFourFive()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFourSixRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFourSix()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFourSevenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFourSeven()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFourEightRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFourEight()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFourNineRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFourNine()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFourTenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFourTen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFourElevenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFourEleven()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFourTwelveRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFourTwelve()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFourThirteenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFourThirteen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFourFourteenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFourFourteen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFourFifteenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFourFifteen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})



// Get slot requests
var GetSlotFiveFiveRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFiveFive()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFiveSixRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFiveSix()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFiveSevenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFiveSeven()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFiveEightRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFiveEight()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFiveNineRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFiveNine()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFiveTenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFiveTen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFiveElevenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFiveEleven()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFiveTwelveRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFiveTwelve()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFiveThirteenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFiveThirteen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFiveFourteenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFiveFourteen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

var GetSlotFiveFifteenRequestsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetSlotFiveFifteen()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})