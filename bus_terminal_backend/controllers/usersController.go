package controllers

import (
	"bytes"
	"crypto/sha512"
	"crypto/subtle"
	"encoding/json"
	"fmt"
	"io/ioutil"
	"log"
	"net/http"
	"new-bus-management-backend/models"
	"new-bus-management-backend/utils"
	"os"
	"strings"
	"github.com/dgrijalva/jwt-go"
)

var (
	auth models.ProbaseTblUser
)

// ResponseResult responses
type ResponseResult struct {
	Error  string `json:"error"`
	Result string `json:"result"`
}

// CreateUserController Function for User registration
var CreateUserController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	account := &models.ProbaseTblUser{}
	err := json.NewDecoder(r.Body).Decode(account) //decode the request body into struct and failed if any error occur
	if err != nil {
		utils.Respond(w, utils.Message(false, "Invalid request"))
		log.Println(utils.Message(false, "Invalid request"))
		return
	}

	resp := account.Create() //Create account
	utils.Respond(w, resp)
})

// AuthenticateUserController Function for user login
var AuthenticateUserController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	var res ResponseResult
	var check models.ProbaseTblUser

	err := json.NewDecoder(r.Body).Decode(&auth)
	if err != nil {
		// If the structure of the body is wrong, return an HTTP error
		w.WriteHeader(http.StatusBadRequest)
		return
	}
	err = models.GetDB().Table("probase_tbl_users").Where("username = ?", auth.Username).First(&check).Error

	if err != nil {
		log.Println(check.Username)
		res.Error = "Invalid Username"
		w.WriteHeader(http.StatusUnauthorized)
		_ = json.NewEncoder(w).Encode(res)
		return
	}

	sha512hash := sha512.Sum512([]byte(auth.Password))
	hashedPassword := fmt.Sprintf("%x", sha512hash)

	rtrn := subtle.ConstantTimeCompare([]byte(strings.ToUpper(check.Password)), []byte(strings.ToUpper(hashedPassword)))

	if rtrn != 1 {
		res.Error = "Invalid password"
		w.WriteHeader(http.StatusUnauthorized)
		_ = json.NewEncoder(w).Encode(res)
		return
	}

	//Worked! Logged In
	check.Password = ""

	//Create JWT token
	tk := &models.Token{UserId: check.ID}
	token := jwt.NewWithClaims(jwt.GetSigningMethod("HS256"), tk)
	tokenString, _ := token.SignedString([]byte(os.Getenv("token_password")))
	check.Token = tokenString //Store the token in the response

	_ = json.NewEncoder(w).Encode(check)

})

//ChangePassword change user password
var ChangePassword = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
	err := json.NewDecoder(r.Body).Decode(&auth)
	if err != nil {
		// If the structure of the body is wrong, return an HTTP error
		w.WriteHeader(http.StatusBadRequest)
		return
	}
	url := os.Getenv("probase_password_change_url")
	manager := os.Getenv("probase_manager_username")
	managerToken := os.Getenv("probase_manager_token")

	fmt.Println("URL:>", url)
	var str = fmt.Sprintf(`{"auth":{"username":"%s","service_token":"%s"},"payload":{"username":"%s","password":"%s"}}`, manager, managerToken, auth.Username, auth.Password)
	var jsonStr = []byte(str)
	req, err := http.NewRequest("POST", url, bytes.NewBuffer(jsonStr))
	req.Header.Set("Content-Type", "application/json")

	client := &http.Client{}
	response, err := client.Do(req)
	if err != nil {
		resp := utils.Message(false, "success")
		resp["data"] = err
		utils.Respond(w, resp)
		return
	}
	defer response.Body.Close()
	var raw map[string]interface{}
	body, _ := ioutil.ReadAll(response.Body)

	if err := json.Unmarshal(body, &raw); err != nil {
		resp := utils.Message(false, "success")
		resp["data"] = err
		utils.Respond(w, resp)
		return
	}

	resp := utils.Message(true, "success")
	resp["data"] = raw
	utils.Respond(w, resp)
})

//ResetPassword change user password
var ResetPassword = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
	err := json.NewDecoder(r.Body).Decode(&auth)
	if err != nil {
		// If the structure of the body is wrong, return an HTTP error
		w.WriteHeader(http.StatusBadRequest)
		return
	}
	url := os.Getenv("probase_password_reset_url")

	fmt.Println("URL:>", url)
	var str = fmt.Sprintf(`{"payload":{"username":"%s"}}`, auth.Username)
	var jsonStr = []byte(str)
	req, err := http.NewRequest("POST", url, bytes.NewBuffer(jsonStr))
	req.Header.Set("Content-Type", "application/json")

	client := &http.Client{}
	response, err := client.Do(req)
	if err != nil {
		resp := utils.Message(false, "success")
		resp["data"] = err
		utils.Respond(w, resp)
		return
	}
	defer response.Body.Close()
	var raw map[string]interface{}
	body, _ := ioutil.ReadAll(response.Body)

	if err := json.Unmarshal(body, &raw); err != nil {
		resp := utils.Message(false, "success")
		resp["data"] = err
		utils.Respond(w, resp)
		return
	}

	resp := utils.Message(true, "success")
	resp["data"] = raw
	utils.Respond(w, resp)
})
