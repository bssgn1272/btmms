package models

import (
	"fmt"
	"github.com/jinzhu/gorm"
	"log"
	"new-bus-management-backend/utils"
	"time"
)

type EdBusRoute struct {
	gorm.Model
	EndRoute string `json:"end_route"`
	StartRoute string `json:"start_route"`
	RouteCode string `json:"route_code"`
	RouteFare int `json:"route_fare"`
	RouteName string `json:"route_name"`
	RouteUUID string `json:"route_uuid"`
	SourceState string `json:"source_state"`
	Parent int `gorm:"default: 0;"`
	SubRoutes []*EdSubRoute `json:"sub_routes"`
}

type EdSubRoute struct {
	gorm.Model
	EndRoute string `json:"end_route"`
	Order int `json:"order"`
	RouteName string `json:"route_name"`
	RouteFare int `json:"route_fare"`
	RouteCode string `json:"route_code"`
	RouteUUID string `json:"route_uuid"`
	SourceSlate string `json:"source_slate"`
	StartRoute string `json:"start_route"`
	EdBusRouteID uint `gorm:"ForeignKey: EdBusRouteID" json:"ed_bus_route_id"`
}

type EdSlotMapping struct {
	gorm.Model
	Slot string `sql:"index"json:"slot"`
	Gate string `json:"gate"`
}

type NewRoute struct {
	EdBusRoute
	EdSubRoute
	EdReservation
	EdSlotMapping
}

// create town
func (edBusRoute *EdBusRoute) Create() map[string]interface{} {

	/*if validErrs := time.Validate(); len(validErrs) > 0 {
		err := map[string]interface{}{"validationError": validErrs}
		return err
	}*/

	err := GetDB().Create(edBusRoute).Error

	if err != nil {
		return nil
	}

	resp := utils.Message(true, "success")
	resp["routes"] = edBusRoute
	log.Println(resp)
	return resp
}

func (edBusRoute *EdBusRoute) Update(id uint) map[string]interface{} {

	//var newRoute *EdBusRoute
	/*if validErrs := time.Validate(); len(validErrs) > 0 {
		err := map[string]interface{}{"validationError": validErrs}
		return err
	}*/
	//err := db.Preload("SubRoutes").Model(EdBusRoute{}).Find(&newRoute, id).Error
	//
	//newRoute.RouteCode = edBusRoute.RouteCode
	//newRoute.EndRoute = edBusRoute.EndRoute
	//newRoute.Parent = edBusRoute.Parent
	//newRoute.RouteFare = edBusRoute.RouteFare
	//newRoute.RouteName = edBusRoute.RouteName
	//newRoute.RouteUUID = edBusRoute.RouteUUID
	//newRoute.SourceState = edBusRoute.SourceState
	//newRoute.StartRoute = edBusRoute.StartRoute
	//newRoute.SubRoutes = edBusRoute.SubRoutes


	//GetDB().Save(&newRoute)

	err := GetDB().Model(EdBusRoute{}).Where("id = ?", id).Update(edBusRoute).Error

	if err != nil {
		return nil
	}

	resp := utils.Message(true, "success")
	resp["travel_routes"] = edBusRoute
	log.Println(resp)
	return resp
}


// get towns
func GetRoutes() []*EdReservation {
	//m := make(map[string]interface{})
	//
	//
	//v := []interface{}{}\
	//test := make([]*EdReservation, 0)
	edBusRoutes := make([]*EdReservation, 0)
	//_ = GetDB().Preload("EdSlotMapping").Model(EdReservation{}).Find(&test)
	err := GetDB().Preload("EdBusRoute").Preload("EdBusRoute.SubRoutes").Preload("EdSlotMappings").Model(EdReservation{}).Where(EdReservation{ReservationStatus: "A"}).Find(&edBusRoutes).Error

	//err := GetDB().Table("ed_reservations").Select("ed_bus_routes.*, ed_sub_routes.*, ed_reservations.reserved_time as reseved_date, ed_reservations.time, ed_slot_mappings.gate").Joins("left join ed_bus_routes on ed_bus_routes.id=ed_reservations.ed_bus_route_id").Joins("LEFT OUTER JOIN ed_sub_routes on ed_bus_routes.id = ed_sub_routes.ed_bus_route_id").Joins("left join ed_slot_mappings on ed_reservations.slot = ed_slot_mappings.slot").Order("ed_reservations.reserved_time ASC").Order("ed_reservations.time ASC").Find(&edBusRoutes).Error


	//for  i := 0; i < len(edBusRoutes); i++ {
	//	appendBusRoutes[i].EdReservation.EdSlotMapping, test[i].EdSlotMapping
	//
	//}
	log.Println("TEST", err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return edBusRoutes
}



func GetRouteByCode(code string) []*EdReservation {

	log.Println("CHECKING>>>>>>>>>>>>>>",code)
	t := time.Now()

	edBusRoutes := make([]*EdReservation, 0)
	err := GetDB().Joins("JOIN ed_bus_routes ON ed_reservations.ed_bus_route_id=ed_bus_routes.id").Joins("JOIN ed_slot_mappings ON ed_reservations.slot=ed_slot_mappings.slot").Preload("EdBusRoute" ).Preload("EdBusRoute.SubRoutes").Preload("EdSlotMappings").Model(EdReservation{}).Where("ed_reservations.reservation_status = ? AND ed_bus_routes.route_code = ? AND ed_reservations.reserved_time > ?","A", code, t).Find(&edBusRoutes).Error



	fmt.Println("TEST", err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return edBusRoutes
}


func(edBusRoutes *EdReservation) GetRouteByRouteID(id uint) *EdReservation {

	err := GetDB().Preload("EdBusRoute").Preload("EdBusRoute.SubRoutes").Preload("EdSlotMappings").Model(EdReservation{}).Where("reservation_status = ? AND ed_bus_route_id = ?","A", id).Find(&edBusRoutes).Error

	log.Println("TEST", err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return edBusRoutes
}

func GetRoutesByUserId(userId uint) []*EdReservation {

	edBusRoutes := make([]*EdReservation, 0)

	err := GetDB().Preload("EdBusRoute").Preload("EdBusRoute.SubRoutes").Preload("EdSlotMappings").Model(EdReservation{}).Where("reservation_status = ? AND user_id = ?","A", userId).Find(&edBusRoutes).Error


	log.Println("TEST", err)
	if err != nil {
		log.Println(err)
		return nil
	}

	return edBusRoutes
}
