package controllers

import (
	"fmt"
	"io/ioutil"
	"log"
	"net/http"
	"net/smtp"
	"net/url"
	"strings"
)

// GetEmailController Function for retrieving town requests for the day
var GetEmailController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("content-type", "application/json")
	queryValues := r.URL.Query()
	_, _ = fmt.Fprintf(w, "hello, %s!\n", queryValues.Get("email"))

	email := queryValues.Get("email")
	user := queryValues.Get("user")
	subject := queryValues.Get("subject")
	text := queryValues.Get("msg")

	// user we are authorizing as
	from := "changalesa8@gmail.com"

	// user we are sending email to
	to := email

	// server we are authorized to send email through
	host := "smtp.gmail.com"

	// Create the authentication for the SendMail()
	// using PlainText, but other authentication methods are encouraged
	auth := smtp.PlainAuth("", from, "fmeogqgokokgqrjx", host)

	mess := `To: %s <%s>
From: "BTMMS" <btmms@napsa.co.zm>
Subject: %s

%s
`

	message := fmt.Sprintf(mess, user, email, subject, text)

	log.Println(message)

	if err := smtp.SendMail(host+":587", auth, from, []string{to}, []byte(message)); err != nil {
		fmt.Println("Error SendMail: ", err)
		return
	}
	fmt.Println("Email Sent!")
})

// GetSMSController blah blah
var GetSMSController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	w.Header().Set("content-type", "application/json")

	queryValues := r.URL.Query()

	_, _ = fmt.Fprintf(w, "hello, %s!\n", queryValues.Get("receiver"))

	receiver := queryValues.Get("receiver")
	msg := queryValues.Get("msg")

	msg = strings.Replace(msg, " ", "_", -1)
	log.Println(msg)

	var URL *url.URL
	URL, err := url.Parse("http://10.10.1.43:13013/napsamobile/pushsms")
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
		// handle error
	}
	defer resp.Body.Close()
	body, err := ioutil.ReadAll(resp.Body)

	log.Println(string(body), resp, msg)

	if err != nil {
		w.WriteHeader(http.StatusInternalServerError)
		_, _ = w.Write([]byte(`{ "message": "` + err.Error() + `" }`))
		return
	}

})
