package controllers

import (
	"../../src/models"
	u "../../src/utils"
	"encoding/json"
	"log"
	"net/http"
)

var CreateUserController = http.HandlerFunc( func(w http.ResponseWriter, r *http.Request) {

	account := &models.User{}
	err := json.NewDecoder(r.Body).Decode(account) //decode the request body into struct and failed if any error occur
	if err != nil {
		u.Respond(w, u.Message(false, "Invalid request"))
		log.Println(u.Message(false, "Invalid request"))
		return
	}

	resp := account.Create() //Create account
	u.Respond(w, resp)
})

var AuthenticateUserController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	account := &models.User{}
	err := json.NewDecoder(r.Body).Decode(account) //decode the request body into struct and failed if any error occur
	if err != nil {
		w.WriteHeader(http.StatusBadRequest)
		u.Respond(w, u.Message(false, "Invalid request"))
		return
	}

	resp := models.Login(account.Username, account.Password)
	u.Respond(w, resp)
})

