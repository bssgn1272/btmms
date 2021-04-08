package models

import (
	"log"
	"new-bus-management-backend/utils"

	"github.com/jinzhu/gorm"
)

// a struct for Time model
type EdTime struct {
	gorm.Model

	TimeOfDay string `json:"time_of_day"`
}

// create town
func (time *EdTime) Create() map[string]interface{} {

	/*if validErrs := time.Validate(); len(validErrs) > 0 {
		err := map[string]interface{}{"validationError": validErrs}
		return err
	}*/

	GetDB().Create(time)

	resp := utils.Message(true, "success")
	resp["time"] = time
	log.Println(resp)
	return resp
}

// get towns
func GetTimes() []*EdTime {

	times := make([]*EdTime, 0)
	err := GetDB().Table("ed_times").Find(&times).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return times
}

// GetLateCancellationTime function to get minutes before cancellation
func GetLateCancellationTime() []*EdTime {
	times := make([]*EdTime, 0)
	err := GetDB().Table("ed_options").Where("option_name = 'minutes_before_cancellation'").Find(&times).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return times
}
