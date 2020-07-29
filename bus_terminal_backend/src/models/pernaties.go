package models

import (
	"log"
	"time"

	u "../../src/utils"
	"github.com/jinzhu/gorm"
)

type EdPenaltyInterval struct {
	gorm.Model
	DueTime     string `json:"due_time"`
	Description string `json:"description"`
	Status      string `json:"status"`
}

type EdPenalty struct {
	gorm.Model
	BusOperatorID uint      `json:"bus_operator_id"`
	BusID         uint      `json:"bus_id"`
	DateBooked    time.Time `json:"date_booked"`
	DatePaid      time.Time `json:"date_paid"`
	Status        string    `json:"status"`
	Type          string    `json:"type"`
}

type EdPenaltyCharge struct {
	gorm.Model
	ChargeID     uint    `json:"charge_id"`
	ChargeDesc   string  `json:"charge_desc"`
	ChargeAmount float32 `json:"charge_amount"`
	ChargeFreq   string  `json:"charge_freq"`
}

// create town
func (time *EdPenaltyInterval) CreatePenaltyTime() map[string]interface{} {

	/*if validErrs := time.Validate(); len(validErrs) > 0 {
		err := map[string]interface{}{"validationError": validErrs}
		return err
	}*/

	GetDB().Create(time)

	resp := u.Message(true, "success")
	resp["time"] = time
	log.Println(resp)
	return resp
}

// get towns
func GetPenaltyTimes() []*EdPenaltyInterval {

	times := make([]*EdPenaltyInterval, 0)
	err := GetDB().Find(&times).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return times
}

// get towns
func GetLatestPenaltyTimes() []*EdPenaltyInterval {

	times := make([]*EdPenaltyInterval, 0)
	err := GetDB().Last(&times).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return times
}

func (time *EdPenaltyInterval) UpdateDueTimeStatus(id uint) map[string]interface{} {
	db.Model(&time).Where("id = ?", id).Updates(EdPenaltyInterval{Status: time.Status})

	log.Println(time.Status)

	resp := u.Message(true, "success")
	resp["time"] = time
	log.Println(resp)
	return resp
}

// create town
func (penalty *EdPenalty) CreatePenalty() map[string]interface{} {

	/*if validErrs := time.Validate(); len(validErrs) > 0 {
		err := map[string]interface{}{"validationError": validErrs}
		return err
	}*/

	GetDB().Create(penalty)

	resp := u.Message(true, "success")
	resp["penalty"] = penalty
	log.Println(resp)
	return resp
}

// get towns
func GetPenalties() []*EdPenalty {

	penalties := make([]*EdPenalty, 0)
	err := GetDB().Find(&penalties).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return penalties
}

// get towns
func GetLatestPenalty() []*EdPenalty {

	penalties := make([]*EdPenalty, 0)
	err := GetDB().Last(&penalties).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return penalties
}

//GetPenaltyCharge hit db
func GetPenaltyCharge(id string) []*EdPenaltyCharge {
	penaltyCharge := make([]*EdPenaltyCharge, 0)

	err := GetDB().Raw("CALL ed_get_cancellation_charge(?)", id).Scan(&penaltyCharge).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return penaltyCharge
}

//GetLoadingFee hit db
func GetLoadingFee(id string) []*EdPenaltyCharge {
	loadingFee := make([]*EdPenaltyCharge, 0)

	err := GetDB().Raw("CALL ed_get_loading_fee(?)", id).Scan(&loadingFee).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return loadingFee
}
