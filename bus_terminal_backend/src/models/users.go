package models

import (
	u "../../src/utils"
	"github.com/dgrijalva/jwt-go"
	"github.com/jinzhu/gorm"
	"golang.org/x/crypto/bcrypt"
	"log"
	"net/url"
	"os"
	"regexp"
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

// Variables for regular expressions

var (
	regexpUsername = regexp.MustCompile("^[^0-9]+$")
	regexpRole = regexp.MustCompile("^[^0-9]+$")
	regexpEmail = regexp.MustCompile("^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$")
	regexpPhone = regexp.MustCompile(`^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$`)
	regexpPassword = regexp.MustCompile("^[^0-9]+$")
)

//Validate incoming user details...
func (account *User) Validate() url.Values  {

	errs := url.Values{}


	if account.Username == "" {
		errs.Add("username", "The username field is required!")
	}


	if account.Password == "" {
		errs.Add("password", "The password field is required!")
	}

	if len(account.Password) < 6 {
		errs.Add("title", "The password must be 6 or more chars!")
	}


	temp := &User{}

	err := GetDB().Table("users").Where("username = ?", account.Username).First(temp).Error

	log.Println(err)

	if err != nil && err != gorm.ErrRecordNotFound {
		errs.Add("connection", "Connection error. Please retry")
	}
	if temp.Username != "" {
		errs.Add("duplicate", "Username already in use by another user.")
	}

	if !regexpUsername.Match([]byte(account.Username)) {
		errs.Add("user_name", "The username field should be valid!")
	}

	if !regexpRole.Match([]byte(account.Role)) {
		errs.Add("user_name", "The username field should be valid!")
	}
	if !regexpEmail.Match([]byte(account.Email)) {
		errs.Add("email", "The email field should be valid!")
	}
	if !regexpPhone.Match([]byte(account.Phone)) {
		errs.Add("phone", "The phone number field should be valid!")
	}
	if !regexpPassword.Match([]byte(account.Username)) {
		errs.Add("user_name", "The username field should be valid!")
	}

	log.Println(errs)

	return errs
}

// create user function
func (account *User) Create() (map[string] interface{}) {

	if validErrs := account.Validate(); len(validErrs) > 0 {
		err := map[string]interface{}{"validationError": validErrs}
		return err
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



