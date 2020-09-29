package models

import (
	"log"
	"new-bus-management-backend/utils"
	"time"
	"github.com/jinzhu/gorm"
)

// EdAccessControl struct to rep accessControl model
type EdAccessControl struct {
	gorm.Model
	BusScheduleID int       `gorm:"default:'0'" json:"bus_schedule_id"`
	DeactivatedAt time.Time `json:"deactivated_at"`
	Status        string    `gorm:"default:'A'" json:"status"`
}

// EdSmsNotification struct to rep EdSmsNotification model
type EdSmsNotification struct {
	gorm.Model
	BusScheduleID int       `gorm:"default:'0'" json:"bus_schedule_id"`
	DeactivatedAt time.Time `json:"deactivated_at"`
	Msisdn        string    `json:"msisdn"`
	Status        string    `gorm:"default:'A'" json:"status"`
}

// EdAccessReady struct to rep accessControl model
type EdAccessReady struct {
	gorm.Model
	BusScheduleID int       `gorm:"default:'0'" json:"bus_schedule_id"`
	BusID         int       `gorm:"default:'0'" json:"bus_id"`
	DeactivatedAt time.Time `json:"deactivated_at"`
}

// Create accessControl
func (accessControl *EdAccessControl) Create() map[string]interface{} {

	GetDB().Create(accessControl)

	resp := utils.Message(true, "success")
	resp["accessControl"] = accessControl
	log.Println(resp)
	return resp
}

// Create smsNotification
func (smsNotification *EdSmsNotification) Create() map[string]interface{} {

	GetDB().Create(smsNotification)

	resp := utils.Message(true, "success")
	resp["smsNotification"] = smsNotification
	log.Println(resp)
	return resp
}

// GetAccessControls Retrieve accessControls
func GetAccessControls() []*EdAccessControl {

	accessControls := make([]*EdAccessControl, 0)
	err := GetDB().Table("ed_access_controls").Find(&accessControls).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return accessControls
}

// GetAccessReady Retrieve accessControls
func GetAccessReady(minBefore string, minAfter string) []*EdAccessReady {

	accessReady := make([]*EdAccessReady, 0)
	err := GetDB().Raw("CALL ed_pending_access_activation(?, ?)", minBefore, minAfter).Scan(&accessReady).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return accessReady
}

// GetDenyReady Retrieve accessControls
func GetDenyReady() []*EdAccessReady {

	accessReady := make([]*EdAccessReady, 0)
	err := GetDB().Raw("SELECT a.id, a.bus_schedule_id, b.bus_id, a.deactivated_at, a.created_at, a.updated_at, a.deleted_at FROM ed_access_controls a JOIN ed_reservations b ON a.bus_schedule_id = b.id WHERE a.status = 'A' AND deactivated_at <= NOW()").Scan(&accessReady).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return accessReady
}

// GetArrivalNotificationReady Retrieve accessControls
func GetArrivalNotificationReady(minBefore string, minAfter string, minBeforeEntry string) []*EdSmsNotification {

	smsNotification := make([]*EdSmsNotification, 0)
	err := GetDB().Raw("CALL ed_pending_sms_notification(?, ?, ?)", minBefore, minAfter, minBeforeEntry).Scan(&smsNotification).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return smsNotification
}

// GetDepartureNotificationReady Retrieve accessControls
func GetDepartureNotificationReady() []*EdSmsNotification {

	smsNotification := make([]*EdSmsNotification, 0)
	err := GetDB().Raw("SELECT a.id, a.bus_schedule_id, b.bus_id, a.deactivated_at, a.msisdn, a.created_at, a.updated_at, a.deleted_at FROM ed_sms_notifications a JOIN ed_reservations b ON a.bus_schedule_id = b.id WHERE a.status = 'A' AND deactivated_at <= NOW()").Scan(&smsNotification).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return smsNotification
}

// GetPendingAccessControls Retrieve accessControls
func GetPendingAccessControls() []*EdAccessControl {

	accessControls := make([]*EdAccessControl, 0)
	err := GetDB().Table("ed_access_controls").Where("status = 'A'").Find(&accessControls).Error
	log.Println(err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return accessControls
}

// Update accessControls Table
func (accessControl *EdAccessControl) Update(id uint) map[string]interface{} {

	db.Model(&accessControl).Where("id = ?", accessControl.ID).Updates(EdAccessControl{BusScheduleID: accessControl.BusScheduleID, DeactivatedAt: accessControl.DeactivatedAt, Status: accessControl.Status})

	resp := utils.Message(true, "success")
	resp["accessControl"] = accessControl
	log.Println(resp)
	return resp
}

// Update smsNotification Table
func (smsNotification *EdSmsNotification) Update(id uint) map[string]interface{} {

	db.Model(&smsNotification).Where("id = ?", smsNotification.ID).Updates(EdSmsNotification{BusScheduleID: smsNotification.BusScheduleID, DeactivatedAt: smsNotification.DeactivatedAt, Status: smsNotification.Status, Msisdn: smsNotification.Msisdn})

	resp := utils.Message(true, "success")
	resp["smsNotification"] = smsNotification
	log.Println(resp)
	return resp
}

// Close accessControls Table
func (accessControl *EdAccessControl) Close() map[string]interface{} {

	db.Model(&accessControl).Where("id = ?", accessControl.ID).Updates(EdAccessControl{BusScheduleID: accessControl.BusScheduleID, DeactivatedAt: accessControl.DeactivatedAt, Status: "D"})

	log.Println(accessControl.ID)
	resp := utils.Message(true, "success")
	resp["accessControl"] = accessControl
	log.Println(resp)
	return resp
}
