package controllers

import (
	"encoding/json"
	"fmt"
	"io/ioutil"
	"log"
	"net/http"
	"net/url"
	"new-bus-management-backend/models"
	"new-bus-management-backend/utils"
	"strconv"
	"time"

	"github.com/bhargav175/noop"
	"github.com/gorilla/mux"
)

// CreateOptionController Function for Creating Slots
var CreateOptionController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	option := &models.EdOption{}

	err := json.NewDecoder(r.Body).Decode(option)
	if err != nil {
		utils.Respond(w, utils.Message(false, "Error while decoding request body"))
		return
	}

	resp := option.Create()
	utils.Respond(w, resp)
})

// GetOptionsController Function for retrieving Options
var GetOptionsController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	data := models.GetOptions()
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// GetOptionController Function for retrieving Option
var GetOptionController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	id, _ := strconv.Atoi(params["id"])
	data := models.GetOption(uint(id))
	resp := utils.Message(true, "success")
	resp["data"] = data
	log.Println(resp)
	utils.Respond(w, resp)
})

// UpdateOptionController Function for Updating Slots
var UpdateOptionController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	params := mux.Vars(r)
	id, _ := strconv.Atoi(params["id"])

	option := &models.EdOption{}

	err := json.NewDecoder(r.Body).Decode(option)
	if err != nil {
		utils.Respond(w, utils.Message(false, "Error while decoding request body"))
		return
	}

	resp := option.Update(uint(id))
	utils.Respond(w, resp)
})

//RunAccessControl Long running job to grant and revoke access to buses
func RunAccessControl() {
	for {
		options := models.GetOptions()
		minutesBeforeEntryActivation := "60"
		minutesAfterExitDeactivation := "60"

		for _, option := range options {
			if option.OptionName == "minutes_before_entry_activation" {
				minutesBeforeEntryActivation = option.OptionValue
			}

			if option.OptionName == "minutes_after_exit_deactivation" {
				minutesAfterExitDeactivation = option.OptionValue
			}
		}
		fmt.Println("Processing Activations: minutes_before_entry_activation: ", minutesBeforeEntryActivation)
		accessReady := models.GetAccessReady(minutesBeforeEntryActivation, minutesAfterExitDeactivation)
		for _, aReady := range accessReady {
			accessControl := &models.EdAccessControl{}
			accessControl.BusScheduleID = aReady.BusScheduleID
			accessControl.DeactivatedAt = aReady.DeactivatedAt
			accessControl.Status = "A"

			url := fmt.Sprintf("http://10.70.3.55:8082/cosec/api.svc/v2/user?action=set;id=%d;active=1", aReady.BusID)
			resp, err := http.Get(url)
			if err != nil {
				print(err)
			} else {
				body, err := ioutil.ReadAll(resp.Body)
				if err != nil {
					print(err)
				} else {
					fmt.Print(string(body))
					accessControl.Create()
					fmt.Printf("Activated: Bus Schedule ID: %d, Bus ID: %d\n", aReady.BusScheduleID, aReady.BusID)
				}
				resp.Body.Close()
			}
		}

		fmt.Println("Processing Deactivations: minutes_after_exit_deactivation: ", minutesAfterExitDeactivation)
		denyReady := models.GetDenyReady()
		for _, dReady := range denyReady {
			accessControl := &models.EdAccessControl{}
			accessControl.ID = dReady.ID
			accessControl.BusScheduleID = dReady.BusScheduleID
			accessControl.DeactivatedAt = dReady.DeactivatedAt
			accessControl.Status = "D"

			url := fmt.Sprintf("http://10.70.3.55:8082/cosec/api.svc/v2/user?action=set;id=%d;active=0", dReady.BusID)
			resp, err := http.Get(url)
			if err != nil {
				print(err)
			} else {
				resp.Body.Close()
				body, err := ioutil.ReadAll(resp.Body)
				if err != nil {
					print(err)
				} else {
					fmt.Print(string(body))
					accessControl.Update(dReady.ID)
					fmt.Printf("Deactivated: Bus Schedule ID: %d, Bus ID: %d\n", dReady.BusScheduleID, dReady.BusID)
				}
				resp.Body.Close()
			}
		}

		time.Sleep(5 * time.Second)
	}
}

//RunSMSNotifications Long running job to grant and revoke access to buses
func RunSMSNotifications() {
	for {
		options := models.GetOptions()
		minutesBeforeEntryNotification := "10"
		minutesBeforeExitNotification := "1"
		minutesBeforeEntry := "5"

		for _, option := range options {
			if option.OptionName == "minutes_before_arrival_notification" {
				minutesBeforeEntryNotification = option.OptionValue
			}

			if option.OptionName == "minutes_before_departure_notification" {
				minutesBeforeExitNotification = option.OptionValue
			}

			if option.OptionName == "minutes_before_entry" {
				minutesBeforeEntry = option.OptionValue
			}
		}
		fmt.Println("Processing Arrival Notifications: minutes_before_arrival_notification: ", minutesBeforeEntryNotification)
		smsReady := models.GetArrivalNotificationReady(minutesBeforeEntryNotification, minutesBeforeExitNotification, minutesBeforeEntry)
		for _, sReady := range smsReady {
			smsNotification := &models.EdSmsNotification{}
			smsNotification.BusScheduleID = sReady.BusScheduleID
			smsNotification.DeactivatedAt = sReady.DeactivatedAt
			smsNotification.Msisdn = sReady.Msisdn
			smsNotification.Status = "A"

			sms(smsNotification.Msisdn, fmt.Sprintf("Remainder: You're expected to arrive at the bus terminus for loading in the next %s minute(s)", minutesBeforeEntryNotification))
			smsNotification.Create()
		}

		fmt.Println("Processing Departure Notifications: minutes_before_departure_notification: ", minutesBeforeExitNotification)
		denyReady := models.GetDepartureNotificationReady()
		for _, dReady := range denyReady {
			smsNotification := &models.EdSmsNotification{}
			smsNotification.ID = dReady.ID
			smsNotification.BusScheduleID = dReady.BusScheduleID
			smsNotification.DeactivatedAt = dReady.DeactivatedAt
			smsNotification.Msisdn = dReady.Msisdn
			smsNotification.Status = "D"

			sms(smsNotification.Msisdn, fmt.Sprintf("Remainder: You're expected to depart from the bus terminus in the next %s minute(s)", minutesBeforeExitNotification))
			smsNotification.Update(smsNotification.ID)
		}

		time.Sleep(5 * time.Second)
	}
}

func sms(receiver string, msg string) {
	log.Println(msg)
	var URL *url.URL
	URL, err := URL.Parse("http://10.10.1.43:13013/napsamobile/pushsms")
	if err != nil {
		return
	}

	parameters := url.Values{}
	parameters.Add("smsc", "zamtelsmsc")
	parameters.Add("username", "napsamobile")
	parameters.Add("password", "napsamobile@kannel")
	parameters.Add("from", "BTMMS")
	parameters.Add("to", receiver)
	parameters.Add("text", msg)

	URL.RawQuery = parameters.Encode()

	uri := URL.String()
	fmt.Printf("Encoded URL is %q\n", URL.String())
	log.Println(uri)

	resp, err := http.Get(uri)
	if err != nil {
		noop := noop.Noop
		noop()
	}
	defer resp.Body.Close()
	body, err := ioutil.ReadAll(resp.Body)

	log.Println(string(body), resp, msg)

	if err != nil {
		/* w.WriteHeader(http.StatusInternalServerError)
		_, _ = w.Write([]byte(`{ "message": "` + err.Error() + `" }`)) */
		return
	}

}
