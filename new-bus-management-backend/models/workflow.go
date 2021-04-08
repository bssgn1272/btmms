package models

import (

	"github.com/jinzhu/gorm"
	"log"
	"new-bus-management-backend/utils"
	"regexp"
)

type EdWorkFlow struct {
	gorm.Model
	Mode string `json:"mode"`
	Description string `json:"description"`
	Status string `gorm:"default:'Inactive'" json:"status"`
}

var (
	regexpMode = regexp.MustCompile("^[^0-9]+$")
	regexpDescription = regexp.MustCompile("^[^0-9]+$")
)


//Validate incoming user details...
//func (mode *EdWorkFlow) Validate() url.Values  {
//
//	errs := url.Values{}
//
//
//	if mode.Mode == "" {
//		errs.Add("mode", "The mode field is required!")
//	}
//
//	if mode.Description == "" {
//		errs.Add("Description", "The Description field is required!")
//	}
//
//
//
//
//	temp := &EdWorkFlow{}
//
//	err := GetDB().Where("username = ?", mode.Mode).First(temp).Error
//
//	log.Println(err)
//
//	if err != nil && err != gorm.ErrRecordNotFound {
//		errs.Add("connection", "Connection error. Please retry")
//	}
//	if temp.Mode != "" {
//		errs.Add("duplicate", "Mode already in use by another user.")
//	}
//
//	if !regexpMode.Match([]byte(mode.Mode)) {
//		errs.Add("user_name", "The mode field should be valid!")
//	}
//	if !regexpDescription.Match([]byte(mode.Description)) {
//		errs.Add("user_name", "The description field should be valid!")
//	}
//
//	log.Println(errs)
//
//	return errs
//}


func (mode *EdWorkFlow) Create() (map[string] interface{}) {


	//if validErrs := mode.Validate(); len(validErrs) > 0 {
	//	err := map[string]interface{}{"validationError": validErrs}
	//	return err
	//}

	GetDB().Create(mode)

	resp := utils.Message(true, "success")
	resp["mode"] = mode
	log.Println(resp)
	return resp

}


// Get workflow modes

func GetModes() ([]*EdWorkFlow) {

	modes := make([]*EdWorkFlow, 0)
	err := GetDB().Find(&modes).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return modes
}

func (workFlow *EdWorkFlow) UpdateModeStatus(id uint) map[string] interface{} {
	db.Model(&workFlow).Where("id = ?", id).Updates(EdWorkFlow{Status:workFlow.Status})

	log.Println(workFlow.Status)

	resp := utils.Message(true, "success")
	resp["workFlow"] = workFlow
	log.Println(resp)
	return resp
}
