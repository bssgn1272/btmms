package models

import (
	"log"
	"new-bus-management-backend/utils"
	"time"
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


type EdPenaltyResult struct {
	EdPenalty
	EdPenaltyCharge
	ProbaseTblBus
	ProbaseTblUser
}

// create town
func (time *EdPenaltyInterval) CreatePenaltyTime() map[string]interface{} {

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

	resp := utils.Message(true, "success")
	resp["time"] = time
	log.Println(resp)
	return resp
}

func (penalty *EdPenalty) UpdatePenalty(id string) map[string]interface{} {
	db.Model(&penalty).Where("id = ?", id).Updates(EdPenalty{BusOperatorID: penalty.BusOperatorID, BusID: penalty.BusID, DateBooked: penalty.DateBooked, DatePaid: time.Now(), Status: penalty.Status, Type: penalty.Type})

	log.Println(penalty.Status)

	resp := utils.Message(true, "success")
	resp["penalty"] = penalty
	log.Println(resp)
	return resp
}

// create town
func (penalty *EdPenalty) CreatePenalty() map[string]interface{} {
	GetDB().Create(penalty)

	resp := utils.Message(true, "success")
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


func GetAccumulatedPenalties(id uint) []*EdPenaltyResult {
	result := make([]*EdPenaltyResult, 0)
	err := GetDB().Table("ed_penalties").Where("bus_operator_id = ?", id).Select("ed_penalties.*,  probase_tbl_users.username, probase_tbl_buses.company, probase_tbl_buses.license_plate,  ed_penalty_types.penalty_type").Joins("left join probase_tbl_users on ed_penalties.bus_operator_id = probase_tbl_users.id").Joins("left join probase_tbl_buses on ed_penalties.bus_id=probase_tbl_buses.id").Joins("left join ed_penalty_types on ed_penalties.penalty_type_id=ed_penalty_types.id").Find(&result).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return result
}
