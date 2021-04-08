package models

import (
	"crypto/hmac"
	"crypto/sha512"
	"encoding/hex"
	"github.com/dgrijalva/jwt-go"
	"github.com/jinzhu/gorm"
	"log"
	"net/url"
	"new-bus-management-backend/utils"
	"os"
	"regexp"
	"time"
)

/*
JWT claims struct
*/
type Token struct {
	UserId uint
	jwt.StandardClaims
}

//a struct to rep user account
type ProbaseTblUser struct {
	gorm.Model
	//Id          uint    `json:"id" sql:"AUTO_INCREMENT" gorm:"primary_key"`
	Username      string    `json:"username"`
	Role          string    `json:"role"`
	Email         string    `json:"email"`
	Mobile        string    `json:"mobile"`
	Password      string    `json:"password"`
	Token         string    `json:"token"`
	Uuid          string    `json:"uuid"`
	Nrc           string    `json:"nrc"`
	AccountStatus string    `json:"account_status"`
	OperatorRole  string    `json:"operator_role"`
	Pin           string    `json:"pin"`
	TmpPin        string    `json:"tmp_pin"`
	Company       string    `json:"company"`
	AccountType   string    `json:"account_type"`
	AccountNumber string    `json:"account_number"`
	InsertedAt    time.Time `json:"inserted_at"`
	Status        string `gorm:"default:'active'" json:"status"`
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
func (account *ProbaseTblUser) Validate() url.Values  {

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


	temp := &ProbaseTblUser{}

	err := GetDB().Table("probase_tbl_users").Where("username = ?", account.Username).First(temp).Error

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
		errs.Add("user_name", "The Role field should be valid!")
	}
	if !regexpEmail.Match([]byte(account.Email)) {
		errs.Add("email", "The email field should be valid!")
	}
	if !regexpPhone.Match([]byte(account.Mobile)) {
		errs.Add("phone", "The phone number field should be valid!")
	}
	if !regexpPassword.Match([]byte(account.Password)) {
		errs.Add("Password", "The Password field should be valid!")
	}

	log.Println(errs)

	return errs
}

// create user function
func (account *ProbaseTblUser) Create() (map[string] interface{}) {


	//if validErrs := account.Validate(); len(validErrs) > 0 {
	//	err := map[string]interface{}{"validationError": validErrs}
	//	return err
	//}

	hash :=  hmac.New(sha512.New, []byte(os.Getenv( "secretKey")))

	 hash.Write([]byte(account.Password))
	hashedPassword := hex.EncodeToString(hash.Sum(nil))

	log.Println(hashedPassword)
	account.Password = hashedPassword
	log.Println(account.Password)

	GetDB().Create(account)

	if account.ID <= 0 {
		log.Println(utils.Message(false, "Failed to create account, connection error."))
		return utils.Message(false, "Failed to create account, connection error.")
	}

	// Create new JWT token for the newly registered account
	tk := &Token{UserId: account.ID}
	token := jwt.NewWithClaims(jwt.GetSigningMethod("HS256"), tk)
	tokenString, _ := token.SignedString([]byte(os.Getenv("token_password")))
	account.Token = tokenString

	account.Password = "" //delete password

	response := utils.Message(true, "Account has been created")
	response["account"] = account
	log.Println(response)
	return response
}


func (probaseTblUser *ProbaseTblUser) UpdateAccessPermission(id uint) map[string]interface{} {

	db.Model(&probaseTblUser).Where("id = ?", id).Updates(ProbaseTblUser{Status: probaseTblUser.Status})

	log.Println(probaseTblUser.Status)

	resp := utils.Message(true, "success")
	resp["user_permission"] = probaseTblUser
	log.Println(resp)
	return resp
}


// GetLateCancellationTime function to get minutes before cancellation
func GetAllUsers() []*ProbaseTblUser {
	probaseTblUser := make([]*ProbaseTblUser, 0)
	err := GetDB().Model(ProbaseTblUser{}).Find(&probaseTblUser).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return probaseTblUser
}



