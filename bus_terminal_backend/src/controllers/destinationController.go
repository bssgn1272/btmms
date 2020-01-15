package controllers

import(
	"../models"
	u "../utils"
	"encoding/json"
	"log"
	"net/http"
)


// Function for town request function
var CreateTownController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {


	town := &models.Town{}

	err := json.NewDecoder(r.Body).Decode(town)
	if err != nil {
		u.Respond(w, u.Message(false, "Error while decoding request body"))
		return
	}

	resp := town.Create()
	u.Respond(w, resp)
})

// Function for retrieving town requests for the day
var GetTownsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetTowns()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})