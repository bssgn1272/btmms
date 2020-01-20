package models

import (
	u "../../src/utils"
	"github.com/jinzhu/gorm"
	"log"
	"time"
)

// a struct for Days model
type DestinationDayTime struct {
	gorm.Model

	DestinationID uint `json:"destination_id"`
	DayID uint `json:"day_id"`
	TimeID uint `json:"time_id"`
}


// join reservation and reservation struct
type Join struct {
	DestinationDayTime
	Day
	Town
	Time
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
func GetDestinationDayTimes() ([]*Join) {

	destinationDayTimes := make([]*Join, 0)
	day := time.Now().AddDate(0,0,1,).Weekday()
	nextDay := day.String()
	log.Println(day)
	err := GetDB().Table("destination_day_times").Select("towns.town_name, days.day, times.time_of_day").Joins("JOIN towns ON destination_day_times.destination_id = towns.id").Joins("JOIN days ON destination_day_times.day_id = days.id").Joins("JOIN times ON destination_day_times.time_id = times.id").Where("days.day = ?", nextDay).Find(&destinationDayTimes).Error

	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return destinationDayTimes
}



