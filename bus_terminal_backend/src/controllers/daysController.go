package controllers

import(
	"../models"
	u "../utils"
	"encoding/json"
	"log"
	"net/http"
)


// Function for days request function
var CreateDayController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {


	day := &models.EdDay{}

	err := json.NewDecoder(r.Body).Decode(day)
	if err != nil {
		u.Respond(w, u.Message(false, "Error while decoding request body"))
		return
	}

	resp := day.Create()
	u.Respond(w, resp)
})

// Function for retrieving days requests for the day
var GetDaysController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetDays()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})
