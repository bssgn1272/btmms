package controllers

import (
	"crypto/tls"
	"errors"
	"fmt"
	"io/ioutil"
	"log"
	"net"
	"net/http"
	"net/smtp"
	"net/url"
	"os"
	"strings"

	"github.com/bhargav175/noop"
)

type loginAuth struct {
	username, password string
}

func LoginAuth(username, password string) smtp.Auth {
	return &loginAuth{username, password}
}

func (a *loginAuth) Start(server *smtp.ServerInfo) (string, []byte, error) {
	return "LOGIN", []byte(a.username), nil
}

func (a *loginAuth) Next(fromServer []byte, more bool) ([]byte, error) {
	if more {
		switch string(fromServer) {
		case "Username:":
			return []byte(a.username), nil
		case "Password:":
			return []byte(a.password), nil
		default:
			return nil, errors.New("unknown from server")
		}
	}
	return nil, nil
}

// GetEmailController Function for retrieving town requests for the day
var GetEmailController = http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("content-type", "application/json")
	queryValues := r.URL.Query()
	_, _ = fmt.Fprintf(w, "hello, %s!\n", queryValues.Get("email"))

	email := queryValues.Get("email")
	user := queryValues.Get("user")
	subject := queryValues.Get("subject")
	text := queryValues.Get("msg")

	from := os.Getenv("email_from")
	password := os.Getenv("email_pass")
	to := email
	smtpHost := os.Getenv("email_host")
	smtpPort := os.Getenv("email_port")

	conn, err := net.Dial("tcp", smtpHost+":"+smtpPort)
	if err != nil {
		log.Println(err)
	}

	c, err := smtp.NewClient(conn, smtpHost)
	if err != nil {
		log.Println(err)
	}

	tlsconfig := &tls.Config{
		ServerName: smtpHost,
	}

	if err = c.StartTLS(tlsconfig); err != nil {
		println(err)
	}

	auth := LoginAuth(from, password)

	if err = c.Auth(auth); err != nil {
		println(err)
	}

	mess := `To: %s <%s>
From: "BTMMS" <btmms@napsa.co.zm>
Subject: %s

%s
`
	message := fmt.Sprintf(mess, user, email, subject, text)
	log.Println(message)

	err = smtp.SendMail(smtpHost+":"+smtpPort, auth, from, []string{to}, []byte(message))
	if err != nil {
		fmt.Println(err)
		return
	}
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
	URL, err := url.Parse(os.Getenv("sms_url"))
	if err != nil {
		return
	}

	parameters := url.Values{}
	parameters.Add("smsc", os.Getenv("smsc"))
	parameters.Add("username", os.Getenv(("sms_user")))
	parameters.Add("password", os.Getenv("sms_pass"))
	parameters.Add("from", os.Getenv("sms_from"))
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
		w.WriteHeader(http.StatusInternalServerError)
		_, _ = w.Write([]byte(`{ "message": "` + err.Error() + `" }`))
		return
	}

})
