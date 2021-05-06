package models

import (
	"log"
)

// Charge struct to rep Charge model
type Charge struct {
	ChargeDesc   string `gorm:"default:'open'" json:"charge_desc"`
	ChargeAmount string `gorm:"default:'open'" json:"charge_amount"`
	ChargeFreq   string `gorm:"default:'open'" json:"charge_freq"`
}

// GetCharges Retrieve charges
func GetCharges() []*Charge {

	options := make([]*Charge, 0)
	err := GetDB().Table("probase_tbl_charges").Where("charge_type IN ('A', 'B')").Find(&options).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return options
}
