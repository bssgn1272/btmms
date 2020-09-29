package models

import (
	"log"
)

type ProbaseTblBus struct {
	ID uint `json:"id"`
	LicensePlate string `json:"license_plate"`
	Uid string `json:"uid"`
	Make string `json:"make"`
	OperatorId string `json:"operator_id"`
	Company string `json:"company"`

}


func GetBuses(id string) ([]*ProbaseTblBus) {

	buses := make([]*ProbaseTblBus, 0)
	err := GetDB().Table("probase_tbl_buses").Where("operator_id = ? ", id).Find(&buses).Error
	log.Println("ID-------", id)
	if err != nil {
		log.Println(err)
		return nil
	}

	return buses
}

func GetAvailableBuses(id string) ([]*ProbaseTblBus) {

	buses := make([]*ProbaseTblBus, 0)
	err := GetDB().Raw("CALL ed_get_available_buses(?)", id).Scan(&buses).Error
	log.Println("ID-------", id)
	if err != nil {
		log.Println(err)
		return nil
	}

	return buses
}
