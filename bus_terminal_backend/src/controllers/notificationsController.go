package controllers

import (
	"fmt"
	"io/ioutil"
	"log"
	"net/http"
	"net/smtp"
	"net/url"
	"os"
	"strings"
)

// Function for retrieving town requests for the day
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

	// use we are sending email to
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
		os.Exit(1)
	}
	fmt.Println("Email Sent!")
	//data := d
	//resp := u.Message(true, "success")
	//resp["data"] = data
	//log.Println(resp)
	//u.Respond(w, resp)
})

var GetSMSController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {

	w.Header().Set("content-type", "application/json")
	queryValues := r.URL.Query()
	_, _ = fmt.Fprintf(w, "hello, %s!\n", queryValues.Get("receiver"))

	receiver := queryValues.Get("receiver")

	msg := queryValues.Get("msg")

	msg = strings.Replace(msg, " ", "_", -1)

	//
	//msgs := url.Values{"msg": {msg}}
	//
	//msg = msgs.Encode()
	//
	//msg = strings.Replace(msg, "%0A", "", -1)
	log.Println(msg)


	//uri := "http://10.8.0.10/sms/index.php?sender=" + sender + "&msisdn=" + msisdn + "&" + msg

	var Url *url.URL
	Url, err := url.Parse("http://196.46.196.38:13013/napsamobile/pushsms")
	if err != nil {
		panic("boom")
	}

	parameters := url.Values{}
	parameters.Add("smsc", "zamtelsmsc")
	parameters.Add("username", "napsamobile")
	parameters.Add("password", "napsamobile@kannel")
	parameters.Add("from", "BTMMS")
	parameters.Add("to", receiver)
	parameters.Add("text", msg)


	Url.RawQuery = parameters.Encode()

	uri := Url.String()

	//uri = strings.Replace(uri, "%0A", "", -1)

	fmt.Printf("Encoded URL is %q\n", Url.String())

	//uri, _ = url.QueryUnescape(uri)
	log.Println(uri)

	//req, _ := http.NewRequest("GET", uri, nil)
	//
	//res, err := http.DefaultClient.Do(req)
	//
	//defer res.Body.Close()
	//body, err := ioutil.ReadAll(res.Body)

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
