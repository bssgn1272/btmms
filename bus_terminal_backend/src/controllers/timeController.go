
package controllers

import(
"../models"
u "../utils"
"encoding/json"
"log"
"net/http"
)


// Function for time request function
var CreateTimeController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {


	time := &models.EdTime{}

	err := json.NewDecoder(r.Body).Decode(time)
	if err != nil {
		u.Respond(w, u.Message(false, "Error while decoding request body"))
		return
	}

	resp := time.Create()
	u.Respond(w, resp)
})

// Function for retrieving time requests for the day
var GetTimesController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetTimes()
	resp := u.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	u.Respond(w, resp)
})

