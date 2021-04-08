package models

import(
	"github.com/jinzhu/gorm"
	"log"
	"net/url"
	"new-bus-management-backend/utils"
	"regexp"
)


// a struct for town model
type EdTown struct {
	gorm.Model

	TownName string `json:"town_name"`
}



// Variables for regular expressions
var(
	regexpTownName = regexp.MustCompile("^[^0-9]+$")
)

/*
 This struct function validate the required parameters sent through the http request body
returns message and true if the requirement is met
*/
func (town *EdTown) Validate() url.Values {

	errs := url.Values{}


	if town.TownName== "" {
		errs.Add("town_name", "Town Name should be on the payload!")
	}

	if !regexpTownName.Match([]byte(town.TownName)) {
		errs.Add("town_name", "The town name field should be valid!")
	}

	log.Println(errs)

	return errs
}


// create town
func (town *EdTown) Create() (map[string] interface{}) {

	if validErrs := town.Validate(); len(validErrs) > 0 {
		err := map[string]interface{}{"validationError": validErrs}
		return err
	}

	GetDB().Create(town)

	resp := utils.Message(true, "success")
	resp["town"] = town
	log.Println(resp)
	return resp
}

// get towns
func GetTowns() ([]*EdTown) {

	towns := make([]*EdTown, 0)
	err := GetDB().Table("ed_towns").Find(&towns).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return towns
}
