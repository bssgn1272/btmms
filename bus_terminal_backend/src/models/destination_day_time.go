package models

import (
	u "../../src/utils"
	"github.com/jinzhu/gorm"
	"log"
)

// a struct for Days model
type DestinationDayTime struct {
	gorm.Model

	DestinationID uint `json:"destination_id"`
	DayID uint `json:"day_id"`
	TimeID uint `json:"time_id"`
}


// create DestinationDayTime
func (destinationDayTime *DestinationDayTime) Create() (map[string] interface{}) {


	GetDB().Create(destinationDayTime)

	resp := u.Message(true, "success")
	resp["destinationDayTime"] = destinationDayTime
	log.Println(resp)
	return resp
}

// get DestinationDayTime
func GetDestinationDayTimes() ([]*Day) {

	days := make([]*Day, 0)
	err := GetDB().Table("days").Find(&days).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return days
}


