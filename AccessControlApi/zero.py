import requests
import threading
import time

def zero(host, x):
	for i in range(0, 44999):
		while True:
			data = {
				'ID': i,
				'Name': '0',
				'Card': '0',
				'PIN': '1234',
				'Year': '2000',
				'Month': '01',
				'Day': '01',
				'Hour': '00',
				'Minute': '00',
				'crtz1': '1'
			}
			try:
				requests.post("http://"+host+"/Edcard.htm", data=data, auth=('admin', '888888'))
				print(host + ": " + str(i))
				break
			except Exception as e:
				print(host + ': Exception: ' + str(i))
				print(host + ': ' + str(e))
				time.sleep(5)

hosts = ['10.70.3.101', '10.70.3.102', '10.70.3.103', '10.70.3.104', '10.70.3.105', '10.70.3.106', '10.70.3.107', '10.70.3.108', '10.70.3.109', '10.70.3.110']
#zero('10.70.3.101', 0)
for host in hosts:
	print(host + " Activated")
	thread = threading.Thread(target=zero, args=(host, 0))
	thread.daemon = True
	thread.start()

while True:
	time.sleep(3600)