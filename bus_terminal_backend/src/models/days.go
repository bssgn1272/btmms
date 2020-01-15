package models

import (
	u "../../src/utils"
	"github.com/jinzhu/gorm"
	"log"
	"net/url"
	"regexp"
)

// a struct for Days model
type Day struct {
	gorm.Model

	Day string `json:"day"`
}




// Variables for regular expressions
var(
	regexpDays = regexp.MustCompile("^[^0-9]+$")
)

/*
 This struct function validate the required parameters sent through the http request body
returns message and true if the requirement is met
*/
func (day *Day) Validate() url.Values {

	errs := url.Values{}


	if day.Day== "" {
		errs.Add("day", "Day should be on the payload!")
	}

	if !regexpDays.Match([]byte(day.Day)) {
		errs.Add("day", "The day field should be valid!")
	}

	log.Println(errs)

	return errs
}


// create town
func (day *Day) Create() (map[string] interface{}) {

	if validErrs := day.Validate(); len(validErrs) > 0 {
		err := map[string]interface{}{"validationError": validErrs}
		return err
	}

	GetDB().Create(day)

	resp := u.Message(true, "success")
	resp["day"] = day
	log.Println(resp)
	return resp
}

// get towns
func GetDays() ([]*Day) {

	days := make([]*Day, 0)
	err := GetDB().Table("days").Find(&days).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return days
}

