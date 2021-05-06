from flask import Flask, request, g, redirect, url_for
from flask import render_template
import requests
from bs4 import BeautifulSoup
import warnings
from flask_httpauth import HTTPBasicAuth
from werkzeug.security import generate_password_hash, check_password_hash
import datetime
from flask import request
import pymysql
from flaskext.auth import Auth, AuthUser, login_required, logout
import threading
import time
import xml.etree.ElementTree as ET
import configparser
import os
import sys

config = configparser.ConfigParser()
config.read(os.path.dirname(os.path.realpath(__file__)) + '\config.ini')
#sys.stdout = open(os.path.dirname(os.path.realpath(__file__)) + '\stdout.txt', 'w')
#sys.stderr = open(os.path.dirname(os.path.realpath(__file__)) + '\stderr.txt', 'w')

hosts = []

card = '3842404'
name = card
year = '2030'
month = '2'
day = '28'
hour = 0
minute = 0

app = Flask(__name__,
			static_url_path='', 
			static_folder='web/static',
			template_folder='web/templates')

app.secret_key = 'N4BUdSXUzHxNoO8g'

apiauth = HTTPBasicAuth()
auth = Auth(app, login_url_name='login')

db_host = config['DATABASE']['Host']
db_user = config['DATABASE']['User']
db_password = config['DATABASE']['Password']
db_db = config['DATABASE']['DB']

db = pymysql.connect(host=db_host,
					user=db_user,
					password=db_password,
					db=db_db,
					charset='utf8mb4',
					cursorclass=pymysql.cursors.DictCursor)

with db:
	cur = db.cursor()
	cur.execute("SELECT * FROM hosts WHERE host_status = 'A'")
	rows = cur.fetchall()
	
	for row in rows:
		hosts.append(row)

with db:
	cur = db.cursor()
	cur.execute("SELECT * FROM apiusers WHERE status = 'A'")
	rows = cur.fetchall()
	apiuserz = {}
	for row in rows:
		apiuserz[row['username']] = generate_password_hash(row['passwd'])

def boomevents():
	while True:
		hosts = []
		db = pymysql.connect(host=db_host,
							user=db_user,
							password=db_password,
							db=db_db,
							charset='utf8mb4',
							cursorclass=pymysql.cursors.DictCursor)

		with db:
			cur = db.cursor()
			cur.execute("SELECT * FROM boomgates WHERE status = 'A'")
			rows = cur.fetchall()

			for row in rows:
				hosts.append(row)

		for host in hosts:
			seqNo = 1
			rollOverCount = 0
			try:
				with db:
					cur = db.cursor()
					cur.execute("SELECT * FROM boomseqnos WHERE boom_id = %s", (host['id'],))
					rows = cur.fetchall()
					
					for row in rows:
						rollOverCount = int(row['rollovercount'])
						seqNo = int(row['seqno'])
						seqNo += 1
				
				print('Checking Sequence Number From: ' + host['name'])
				url = "http://%s:%s/device.cgi/command?action=geteventcount"%(host['ip'], host['port'])
				r = requests.get(url, auth=(host['username'], host['password']))
				data = r.content
				if r.status_code == 200 or r.status_code == '200':
					data = data.split('=')
					deviceRollOverCount = data[1]
					deviceSeqNo = data[2]
					deviceRollOverCount = deviceRollOverCount.split('\r\n')
					deviceSeqNo = deviceSeqNo.split('\r\n')
					deviceRollOverCount = deviceRollOverCount[0].strip()
					deviceSeqNo = deviceSeqNo[0].strip()
                    
					if deviceRollOverCount < rollOverCount:
						rollOverCount = deviceRollOverCount
                        
					if deviceSeqNo < seqNo:
						seqNo = 1

				print('Fetching Events From: ' + host['name'])
				url = "http://%s:%s/device.cgi/events?action=getevent&roll-over-count=%s&seq-number=%s&format=xml"%(host['ip'], host['port'], rollOverCount, seqNo)
				r = requests.get(url, auth=(host['username'], host['password']))
				data = r.content
				if r.status_code == 200 or r.status_code == '200':
					tree = ET.fromstring(data)
					
					for responseCode in tree.findall('Response-Code'):
						print(host['name'] +': No new events: ' + responseCode.text)

					for event in tree.findall('Events'):
						if(event.find('detail-1').text) == 0 or event.find('detail-1').text == '0':
							seqNo = int(event.find('seq-No').text)
							
							if seqNo >= 100000:
								query = "UPDATE boomseqnos SET seqno = 1, rollovercount = %s WHERE boom_id = %s"
								with db:
									cur = db.cursor()
									cur.execute(query, ((rollOverCount + 1), host['id']))
									db.commit()
							else:
								query = "UPDATE boomseqnos SET seqno = %s WHERE boom_id = %s"
								with db:
									cur = db.cursor()
									cur.execute(query, (seqNo, host['id']))
									db.commit()
							continue
						seqNo = int(event.find('seq-No').text)
						print('Seq No:' + event.find('seq-No').text)
						print('Date:' + event.find('date').text)
						print('Time:' + event.find('time').text)
						print('Event ID:' + event.find('event-id').text)
						print('Card #:' + event.find('detail-1').text)
						print(host['name'] + '\n')

						query = "INSERT INTO events (event_id, date, time, card_no, boom_id) VALUES (%s, %s, %s, %s, %s)"
						with db:
							cur = db.cursor()
							cur.execute(query, (event.find('event-id').text, event.find('date').text, event.find('time').text, event.find('detail-1').text, host['id']))
							db.commit()
					
						query = "UPDATE boomseqnos SET seqno = %s WHERE boom_id = %s"
						with db:
							cur = db.cursor()
							cur.execute(query, (seqNo, row['id']))
							db.commit()
			except Exception as e:
				print(e)
			time.sleep(3)

boomthread = threading.Thread(target=boomevents)
boomthread.daemon = True
boomthread.start()

def enable_card(hosts, name, card, year, month, day, hour, minute):
	for host in hosts:
		done = False
		for i in range(0, 2999):
			if done:
				break

			page = None
			try:
				page = requests.get("http://" + host['host_ip'] + "/card.htm?page=" + str(i), auth=(host['host_user'], host['host_passwd']))
			except Exception as e:
				print(e)
				continue
			
			soup = BeautifulSoup(page.content, 'html.parser')
			
			for tr in soup.find_all('tr'):
				ths = tr.find_all('th')

				if len(ths) == 7:
					expired = False

					if ths[5].get_text().strip() != '' and ths[5].get_text().strip() != None:
						exp_date_temp = ths[5].get_text().split(' ')
						date_part = exp_date_temp[0].split('-')
						time_part = exp_date_temp[1].split(':')

						expiry_date = datetime.datetime(int(date_part[0]), int(date_part[1]), int(date_part[2]), int(time_part[0]), int(time_part[1]))

						if datetime.datetime.now() > expiry_date:
							expired = True

					if ths[3].get_text().strip() == "Disable" or ths[2].get_text().strip() == '' or ths[2].get_text().strip() == None or expired:
						idd = ths[0].get_text().strip()
						print('ID: ' + idd)

						data = {
							'ID': idd,
							'isEn': '1',
							'Name': name,
							'Card': card,
							'PIN': card,
							'Year': year,
							'Month': month,
							'Day': day,
							'Hour': hour,
							'Minute': minute,
							'crtz1': '1'
						}

						headers = {
							'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
							'Accept-Encoding': 'gzip, deflate',
							'Accept-Language': 'en-US,en;q=0.9',
							'Cache-Control': 'max-age=0',
							'Connection': 'keep-alive',
							'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
							'Host': host['host_ip'],
							'Origin': 'http://' + host['host_ip'],
							'Referer': 'http://' + host['host_ip'] + '/Edcard.htm?ID=' + idd,
							'Upgrade-Insecure-Requests': '1',
							'User-Agent': 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36'
						}

						page = requests.post("http://" + host['host_ip'] + "/Edcard.htm", data=data, headers=headers, auth=(host['host_user'], host['host_passwd']))
						
						soup = BeautifulSoup(page.content, 'html.parser')
						h2 = soup.find_all('h2')
						print(h2)
						if len(h2) > 0:
							if h2[0].get_text().strip() == "Successfully!":
								done = True
								break

def disable_card(hosts, name, card, year, month, day, hour, minute):
	for host in hosts:
		done = False
		for i in range(0, 3000):
			if done:
				break

			page = None
			try:
				page = requests.get("http://" + host['host_ip'] + "/card.htm?page=" + str(i), auth=(host['host_user'], host['host_passwd']))
			except Exception as e:
				print(e)
				continue
			
			soup = BeautifulSoup(page.content, 'html.parser')
			
			for tr in soup.find_all('tr'):
				ths = tr.find_all('th')

				if len(ths) == 7:
					if ths[2].get_text().strip() == card and ths[3].get_text().strip() != 'Disable':
						idd = ths[0].get_text().strip()
						print('ID: ' + idd)

						data = {
							'ID': idd,
							'Name': name,
							'Card': card,
							'PIN': '1234',
							'Year': year,
							'Month': month,
							'Day': day,
							'Hour': hour,
							'Minute': minute,
							'crtz1': '1'
						}

						headers = {
							'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
							'Accept-Encoding': 'gzip, deflate',
							'Accept-Language': 'en-US,en;q=0.9',
							'Cache-Control': 'max-age=0',
							'Connection': 'keep-alive',
							'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
							'Host': host['host_ip'],
							'Origin': 'http://' + host['host_ip'],
							'Referer': 'http://' + host['host_ip'] + '/Edcard.htm?ID=' + idd,
							'Upgrade-Insecure-Requests': '1',
							'User-Agent': 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36'
						}

						page = requests.post("http://" + host['host_ip'] + "/Edcard.htm", data=data, headers=headers, auth=(host['host_user'], host['host_passwd']))
						
						soup = BeautifulSoup(page.content, 'html.parser')
						h2 = soup.find_all('h2')
						print(h2)
						if len(h2) > 0:
							if h2[0].get_text().strip() == "Successfully!":
								done = True
								break

@app.before_request
def init_users():
	admin = AuthUser(username='admin')
	admin.set_and_encrypt_password('888888', salt='123')
	g.users = {'admin': admin}

@apiauth.verify_password
def verify_password(username, password):
	if username in apiuserz:
		return check_password_hash(apiuserz.get(username), password)
	return False

@app.route('/test')
def test():
	return render_template('test.html')

@app.route('/login', methods=['GET', 'POST'])
@app.route('/login/', methods=['GET', 'POST'])
def login():
	if request.method == 'POST':
		username = request.form['username']
		if username in g.users:
			# Authenticate and log in!
			if g.users[username].authenticate(request.form['password']):
				return redirect('/')
	return render_template('login.html')

@app.route('/logout')
@app.route('/logout/')
def dologout():
	logout()
	return redirect(url_for('login'))

@app.route('/')
@app.route('/home')
@app.route('/home/')
@login_required()
def home():
	db = pymysql.connect(host=db_host,
					user=db_user,
					password=db_password,
					db=db_db,
					charset='utf8mb4',
					cursorclass=pymysql.cursors.DictCursor)

	with db:
		cur = db.cursor()
		cur.execute("SELECT * FROM hosts WHERE host_status = 'A'")
		rows = cur.fetchall()

	hosts = []
	for row in rows:
		hosts.append(row)
	
	return render_template('hosts.html', hosts = hosts, page = 1)

@app.route('/booms', endpoint='booms')
@app.route('/booms/', endpoint='booms')
@login_required()
def booms():
	db = pymysql.connect(host=db_host,
					user=db_user,
					password=db_password,
					db=db_db,
					charset='utf8mb4',
					cursorclass=pymysql.cursors.DictCursor)

	with db:
		cur = db.cursor()
		cur.execute("SELECT a.*, b.seqno FROM boomgates a JOIN boomseqnos b ON a.id = b.boom_id WHERE a.status = 'A'")
		rows = cur.fetchall()

	hosts = []
	for row in rows:
		hosts.append(row)
	
	return render_template('booms.html', hosts = hosts, page = 4)

@app.route('/apiusers', endpoint='apiusers')
@app.route('/apiusers/', endpoint='apiusers')
@login_required()
def apiusers():
	db = pymysql.connect(host=db_host,
					user=db_user,
					password=db_password,
					db=db_db,
					charset='utf8mb4',
					cursorclass=pymysql.cursors.DictCursor)

	with db:
		cur = db.cursor()
		cur.execute("SELECT * FROM apiusers")
		rows = cur.fetchall()

	apiusers = []
	for row in rows:
		apiusers.append(row)
	
	return render_template('apiusers.html', apiusers = apiusers, page = 2)

@app.route('/apiuser', methods=['GET', 'POST'], endpoint='apiuser')
@app.route('/apiuser/', methods=['GET', 'POST'], endpoint='apiuser')
@login_required()
def apiuser():
	if request.method == 'POST':
		username = request.form.get('username')
		passwd = request.form.get('password')
		status = request.form.get('status')

		if status is None:
			status = 'I'
		query = "INSERT INTO apiusers (username, passwd, status) VALUES (%s, %s, %s)"
		
		db = pymysql.connect(host=db_host,
					user=db_user,
					password=db_password,
					db=db_db,
					charset='utf8mb4',
					cursorclass=pymysql.cursors.DictCursor)

		with db:
			cur = db.cursor()
			cur.execute(query, (username, passwd, status))
			db.commit()

		return redirect("/apiusers")

	return render_template('apiuser.html', page = 2)

@app.route('/editapiuser', methods=['GET', 'POST'], endpoint='editapiuser')
@app.route('/editapiuser/', methods=['GET', 'POST'], endpoint='editapiuser')
@login_required()
def editapiuser():
	if request.method == 'POST':
		au_id = request.form.get('id')
		username = request.form.get('username')
		passwd = request.form.get('password')
		status = request.form.get('status')

		if status is None:
			status = 'I'
		query = "UPDATE apiusers SET username = %s, passwd = %s, status = %s WHERE id = %s"
		
		db = pymysql.connect(host=db_host,
					user=db_user,
					password=db_password,
					db=db_db,
					charset='utf8mb4',
					cursorclass=pymysql.cursors.DictCursor)

		with db:
			cur = db.cursor()
			cur.execute(query, (username,passwd, status, au_id))
			db.commit()

		return redirect("/apiusers")

	au_id = request.args.get('id')

	query = "SELECT * FROM apiusers WHERE id = %s"
	db = pymysql.connect(host=db_host,
					user=db_user,
					password=db_password,
					db=db_db)

	with db:
		cur = db.cursor()
		cur.execute(query, (au_id,))
		host = cur.fetchone()

	username = host[1]
	passwd = host[2]
	status = host[3]

	return render_template('editapiuser.html', au_id = au_id, username = username, passwd = passwd, status = status, page = 2)

@app.route('/employee', methods=['GET'], endpoint='employee')
@app.route('/employee/', methods=['GET'], endpoint='employee')
@login_required()
def employee():
	return render_template('employee.html', apiusers = apiusers, page = 3)

@app.route('/controller', methods=['GET', 'POST'], endpoint='controller')
@app.route('/controller/', methods=['GET', 'POST'], endpoint='controller')
@login_required()
def controller():
	if request.method == 'POST':
		name = request.form.get('name')
		ip = request.form.get('ip')
		user = request.form.get('user')
		passwd = request.form.get('password')
		status = request.form.get('status')

		if status is None:
			status = 'I'
		query = "INSERT INTO hosts (host_name, host_ip, host_user, host_passwd, host_status) VALUES (%s, %s, %s, %s, %s)"
		
		db = pymysql.connect(host=db_host,
					user=db_user,
					password=db_password,
					db=db_db,
					charset='utf8mb4',
					cursorclass=pymysql.cursors.DictCursor)

		with db:
			cur = db.cursor()
			cur.execute(query, (name, ip, user, passwd, status))
			db.commit()

		return redirect("/")

	return render_template('controller.html', page = 1)

@app.route('/boom', methods=['GET', 'POST'], endpoint='boom')
@app.route('/boom/', methods=['GET', 'POST'], endpoint='boom')
@login_required()
def boom():
	if request.method == 'POST':
		name = request.form.get('name')
		ip = request.form.get('ip')
		user = request.form.get('user')
		passwd = request.form.get('password')
		status = request.form.get('status')
		seqno = request.form.get('seqno')

		if status is None:
			status = 'I'
		query = "INSERT INTO boomgates (name, ip, port, username, password, status) VALUES (%s, %s, '80', %s, %s, %s)"
		seqquery = "INSERT into boomseqnos (boom_id, seqno) VALUES (%s, %s)"
		
		db = pymysql.connect(host=db_host,
					user=db_user,
					password=db_password,
					db=db_db,
					charset='utf8mb4',
					cursorclass=pymysql.cursors.DictCursor)

		with db:
			cur = db.cursor()
			cur.execute(query, (name, ip, user, passwd, status))
			db.commit()
		
		boom_id = 0
		with db:
			cur = db.cursor()
			cur.execute("SELECT MAX(id) boom_id FROM boomgates")
			row = cur.fetchone()
			boom_id = row['boom_id']
		
		with db:
			cur = db.cursor()
			cur.execute(seqquery, (boom_id, seqno))
			db.commit()

		return redirect("/booms")

	return render_template('boom.html', page = 1)

@app.route('/editcontroller', methods=['GET', 'POST'], endpoint='editcontroller')
@app.route('/editcontroller/', methods=['GET', 'POST'], endpoint='editcontroller')
@login_required()
def editcontroller():
	if request.method == 'POST':
		host_id = request.form.get('id')
		name = request.form.get('name')
		ip = request.form.get('ip')
		user = request.form.get('user')
		passwd = request.form.get('password')
		status = request.form.get('status')

		if status is None:
			status = 'I'
		query = "UPDATE hosts SET host_name = %s, host_ip = %s, host_user = %s, host_passwd = %s, host_status = %s WHERE id = %s"
		
		db = pymysql.connect(host=db_host,
					user=db_user,
					password=db_password,
					db=db_db)

		with db:
			cur = db.cursor()
			cur.execute(query, (name, ip, user, passwd, status, host_id))
			db.commit()

		return redirect("/")

	host_id = request.args.get('id')

	query = "SELECT * FROM hosts WHERE id = %s"
	db = pymysql.connect(host=db_host,
					user=db_user,
					password=db_password,
					db=db_db)

	with db:
		cur = db.cursor()
		cur.execute(query, (host_id,))
		host = cur.fetchone()

	name = host[1]
	ip = host[2]
	user = host[3]
	passwd = host[4]
	status = host[5]

	return render_template('editcontroller.html', host_id = host_id, name = name, ip = ip, user = user, passwd = passwd, status = status, page = 1)

@app.route('/editboom', methods=['GET', 'POST'], endpoint='editboom')
@app.route('/editboom/', methods=['GET', 'POST'], endpoint='editboom')
@login_required()
def editboom():
	if request.method == 'POST':
		boom_id = request.form.get('id')
		name = request.form.get('name')
		ip = request.form.get('ip')
		user = request.form.get('user')
		passwd = request.form.get('password')
		status = request.form.get('status')
		seqno = request.form.get('seqno')

		if status is None:
			status = 'I'
		query = "UPDATE boomgates SET name = %s, ip = %s, username = %s, password = %s, status = %s WHERE id = %s"
		seqquery = "UPDATE boomseqnos SET seqno = %s WHERE boom_id = %s"
		
		db = pymysql.connect(host=db_host,
					user=db_user,
					password=db_password,
					db=db_db)

		with db:
			print(name)
			cur = db.cursor()
			cur.execute(query, (name, ip, user, passwd, status, boom_id))
			db.commit()
			cur.execute(seqquery, (seqno, boom_id))
			db.commit()

		return redirect("/booms")

	boom_id = request.args.get('id')

	query = "SELECT a.*, b.seqno FROM boomgates a JOIN boomseqnos b ON a.id = b.boom_id WHERE a.id = %s"
	db = pymysql.connect(host=db_host,
					user=db_user,
					password=db_password,
					db=db_db)

	with db:
		cur = db.cursor()
		cur.execute(query, (boom_id,))
		host = cur.fetchone()

	name = host[1]
	ip = host[2]
	user = host[4]
	passwd = host[5]
	status = host[6]
	seqno = host[7]

	return render_template('editboom.html', boom_id = boom_id, name = name, ip = ip, user = user, passwd = passwd, status = status, seqno = seqno, page = 1)

@app.route('/enable', methods=['POST'])
@app.route('/enable/', methods=['POST'])
#@apiauth.login_required
def enable():
	end_date = datetime.datetime.today() # + datetime.timedelta(days=1)
	date_arr = str(end_date).split(' ')
	date_part = date_arr[0].split('-')

	card = request.form.get('card')
	name = card
	year = date_part[0]
	month = date_part[1]
	day = date_part[2]
	hour = '23'
	minute = '59'

	if "emp" in request.form:
		year = '2050'
		month = '12'
		day = '31'

	for host in hosts:
		thread = threading.Thread(target=enable_card, args=([host,], name, card, year, month, day, hour, minute))
		thread.daemon = True
		thread.start()
	
	if "emp" in request.form:
		return render_template('message.html', message = card + ' has been submitted for enabling')
	else:
		return card + ' has been submitted for enabling'

@app.route('/disable', methods=['POST'])
@app.route('/disable/', methods=['POST'])
#@apiauth.login_required
def disable():
	end_date = datetime.datetime.today() # + datetime.timedelta(days=1)
	date_arr = str(end_date).split(' ')
	date_part = date_arr[0].split('-')
	time_part = date_arr[1].split(':')

	card = request.form.get('card')
	name = card
	year = date_part[0]
	month = date_part[1]
	day = date_part[2]
	hour = time_part[0]
	minute = time_part[1]

	for host in hosts:
		thread = threading.Thread(target=disable_card, args=([host,], name, card, year, month, day, hour, minute))
		thread.daemon = True
		thread.start()

	if "emp" in request.form:
		return render_template('message.html', message = card + ' has been submitted for disabling')
	else:
		return card + ' has been submitted for disabling'

if __name__ == '__main__':
	app.run(host='0.0.0.0')