package models

import (
	"github.com/jinzhu/gorm"
	"log"
	"time"
)

// a struct for Days model
//type EdDestinationDayTime struct {
//	gorm.Model
//
//	DestinationID uint `json:"destination_id"`
//	DayID uint `json:"day_id"`
//	TimeID uint `json:"time_id"`
//}


//// join reservation and reservation struct
//type EdJoin struct {
//	EdDestinationDayTime
//	EdDay
//	EdTown
//	EdTime
//}

// routes struct
type ProbaseTblTravelRoutes struct {
	gorm.Model
	RouteName string `json:"route_name"`
	StartRoute string `json:"start_route"`
	EndRoute string `json:"end_route"`
	RouteCode string `json:"route_code"`
	TicketId string `json:"ticket_id"`
	SourceState string `json:"source_state"`
	RouteUuid string `json:"route_uuid"`
	InsertedAt time.Time `json:"inserted_at"`
	MakerId string `json:"maker_id"`
	CheckerId string `json:"checker_id"`
}

// create DestinationDayTime
//func (destinationDayTime *ProbaseTblTravelRoutes) Create() (map[string] interface{}) {
//
//
//	GetDB().Create(destinationDayTime)
//
//	resp := utils.Message(true, "success")
//	resp["destinationDayTime"] = destinationDayTime
//	log.Println(resp)
//	return resp
//}

// get DestinationDayTime
func GetDestinationDayTimes() ([]*ProbaseTblTravelRoutes) {

	destinationDayTimes := make([]*ProbaseTblTravelRoutes, 0)
	//day := time.Now().AddDate(0,0,1,).Weekday()
	//nextDay := day.String()
	//log.Println(day)
	err := GetDB().Find(&destinationDayTimes).Error

	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return destinationDayTimes
}



