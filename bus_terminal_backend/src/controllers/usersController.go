package controllers

import (
	"../../src/models"
	u "../../src/utils"
	"encoding/json"
	"github.com/dgrijalva/jwt-go"
	"golang.org/x/crypto/bcrypt"
	"log"
	"net/http"
	"os"
)

var(
	auth models.User


)

/*responses*/

type ResponseResult struct {
	Error  string `json:"error"`
	Result string `json:"result"`
}

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

	var res ResponseResult
	var check models.User
	//account := &models.User{}
	//err := json.NewDecoder(r.Body).Decode(account) //decode the request body into struct and failed if any error occur
	//if err != nil {
	//	w.WriteHeader(http.StatusBadRequest)
	//	u.Respond(w, u.Message(false, "Invalid request"))
	//	return
	//}

	// resp := models.Login(account.Username, account.Password)

	// u.Respond(w, resp)


	err := json.NewDecoder(r.Body).Decode(&auth)
	if err != nil {
		// If the structure of the body is wrong, return an HTTP error
		w.WriteHeader(http.StatusBadRequest)
		return
	}
	err = models.GetDB().Table("users").Where("username = ?", auth.Username).First(&check).Error

	if err != nil {
		// res.Error = "Invalid username"
		log.Println(check.Username)
		w.WriteHeader(http.StatusUnauthorized)
		json.NewEncoder(w).Encode(res)
		return
	}

	err = bcrypt.CompareHashAndPassword([]byte(check.Password), []byte(auth.Password))

	if err != nil {
		// res.Error = "Invalid password"
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
	check.Token = tokenString //Store the token in the respon

	_ = json.NewEncoder(w).Encode(check)
})

