package models

import (
	"log"
	"new-bus-management-backend/utils"

	"github.com/jinzhu/gorm"
)

// EdOption struct to rep Option model
type EdOption struct {
	gorm.Model
	OptionName  string `gorm:"default:'open'" json:"option_name"`
	OptionValue string `gorm:"default:'open'" json:"option_value"`
	Status      string `gorm:"default:'open'" json:"status"`
}

// Create option
func (option *EdOption) Create() map[string]interface{} {

	GetDB().Create(option)

	resp := utils.Message(true, "success")
	resp["option"] = option
	log.Println(resp)
	return resp
}

// GetOptions Retrieve options
func GetOptions() []*EdOption {

	options := make([]*EdOption, 0)
	err := GetDB().Table("ed_options").Find(&options).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return options
}

// Update SlotOptionss Table
func (option *EdOption) Update(id uint) map[string]interface{} {

	db.Model(&option).Where("id = ?", option.ID).Updates(EdOption{OptionName: option.OptionName, OptionValue: option.OptionValue, Status: option.Status})

	resp := utils.Message(true, "success")
	resp["option"] = option
	log.Println(resp)
	return resp
}

// Close Options Table
func (option *EdOption) Close() map[string]interface{} {

	db.Model(&option).Where("status = ?", option.ID).Updates(EdOption{OptionName: option.OptionName, OptionValue: option.OptionValue, Status: option.Status})

	log.Println(option.ID)
	resp := utils.Message(true, "success")
	resp["option"] = option
	log.Println(resp)
	return resp
}
