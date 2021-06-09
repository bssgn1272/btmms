package models

import (
	"log"

	"github.com/jinzhu/gorm"
)

type ProbaseTblBus struct {
	ID           uint   `json:"id"`
	LicensePlate string `json:"license_plate"`
	Uid          string `json:"uid"`
	Make         string `json:"make"`
	OperatorId   string `json:"operator_id"`
	Company      string `json:"company"`
}

type EyedBusRoute struct {
	gorm.Model
	EndRoute   string `json:"end_route"`
	StartRoute string `json:"start_route"`
	RouteCode  string `json:"route_code"`
	RouteFare  int    `json:"route_fare"`
	RouteName  string `json:"route_name"`
}

func GetBuses(id string) []*ProbaseTblBus {

	buses := make([]*ProbaseTblBus, 0)
	err := GetDB().Table("probase_tbl_bus").Where("operator_id = ? AND auth_status = 1", id).Find(&buses).Error
	log.Println("ID-------", id)
	if err != nil {
		log.Println(err)
		return nil
	}

	return buses
}

func GetAvailableBuses(id string) []*ProbaseTblBus {

	buses := make([]*ProbaseTblBus, 0)
	err := GetDB().Raw("CALL ed_get_available_buses(?)", id).Scan(&buses).Error
	log.Println("ID-------", id)
	if err != nil {
		log.Println(err)
		return nil
	}

	return buses
}
