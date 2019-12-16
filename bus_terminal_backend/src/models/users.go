package models

import (
	u "../../src/utils"
	"github.com/dgrijalva/jwt-go"
	"github.com/jinzhu/gorm"
	"golang.org/x/crypto/bcrypt"
	"log"
	"os"
	"strings"
)

/*
JWT claims struct
*/
type Token struct {
	UserId uint
	jwt.StandardClaims
}

//a struct to rep user account
type User struct {
	gorm.Model
	Username string `json:"username"`
	Role string `json:"role"`
	Email string `json:"email"`
	Phone string `json:"phone"`
	Password string `json:"password"`
	Token string `json:"token"`
}

//Validate incoming user details...
func (account *User) Validate() (map[string] interface{}, bool) {

	if !strings.Contains(account.Email, "@") {
		log.Println(u.Message(false, "Email is required"))
		return u.Message(false, "Email is required"), false
	}

	if len(account.Password) < 6 {
		log.Println(u.Message(false, "Password is required"))
		return u.Message(false, "Password is required"), false
	}

	//Email must be unique
	temp := &User{}

	//check for errors and duplicate emails
	err := GetDB().Table("users").Where("username = ?", account.Username).First(temp).Error

	log.Println(err)

	if err != nil && err != gorm.ErrRecordNotFound {
		log.Println(u.Message(false, "Connection error. Please retry"))
		return u.Message(false, "Connection error. Please retry"), false
	}
	if temp.Username != "" {
		log.Println(u.Message(false, "Username address already in use by another user."))
		return u.Message(false, "Username already in use by another user."), false
	}

	return u.Message(false, "Requirement passed"), true
}

// create user function
func (account *User) Create() (map[string] interface{}) {

	if resp, ok := account.Validate(); !ok {
		return resp
	}

	hashedPassword, _ := bcrypt.GenerateFromPassword([]byte(account.Password), bcrypt.DefaultCost)
	account.Password = string(hashedPassword)

	GetDB().Create(account)

	if account.ID <= 0 {
		log.Println(u.Message(false, "Failed to create account, connection error."))
		return u.Message(false, "Failed to create account, connection error.")
	}

	// Create new JWT token for the newly registered account
	tk := &Token{UserId: account.ID}
	token := jwt.NewWithClaims(jwt.GetSigningMethod("HS256"), tk)
	tokenString, _ := token.SignedString([]byte(os.Getenv("token_password")))
	account.Token = tokenString

	account.Password = "" //delete password

	response := u.Message(true, "Account has been created")
	response["account"] = account
	log.Println(response)
	return response
}



