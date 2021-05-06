import requests
from bs4 import BeautifulSoup
import warnings

warnings.filterwarnings("ignore", category=UserWarning, module='bs4')

hosts = ['192.168.100.97']

card = '3842404'
name = card
year = '2030'
month = '2'
day = '28'
hour = 0
minute = 0
op = '0'

def enable(hosts, name, card, year, month, day, hour, minute):
	for host in hosts:
		done = False
		for i in range(0, 3000):

			if done:
				break

			page = requests.get("http://" + host + "/card.htm?page=" + str(i), auth=('admin', '888888'))
			
			soup = BeautifulSoup(page.content, 'html.parser')
			
			for tr in soup.find_all('tr'):
				ths = tr.find_all('th')

				if len(ths) == 7:
					identity = int(ths[0].get_text()) - 1

					if ths[3].get_text().strip() == "Disable":
						idd = ths[0].get_text().strip()
						print('ID: ' + idd)

						data = {
							'ID': idd,
							'isEn': '1',
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
							'Host': host,
							'Origin': 'http://' + host,
							'Referer': 'http://' + host + '/Edcard.htm?ID=' + idd,
							'Upgrade-Insecure-Requests': '1',
							'User-Agent': 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36'
						}

						page = requests.post("http://" + host + "/Edcard.htm", data=data, headers=headers, auth=('admin', '888888'))
						
						soup = BeautifulSoup(page.content, 'html.parser')
						h2 = soup.find_all('h2')
						print(h2)
						if len(h2) > 0:
							if h2[0].get_text().strip() == "Successfully!":
								done = True
								break

def disable(hosts, name, card, year, month, day, hour, minute):
	for host in hosts:
		done = False
		for i in range(0, 3000):

			if done:
				break

			page = requests.get("http://" + host + "/card.htm?page=" + str(i), auth=('admin', '888888'))
			
			soup = BeautifulSoup(page.content, 'html.parser')
			
			for tr in soup.find_all('tr'):
				ths = tr.find_all('th')

				if len(ths) == 7:
					identity = int(ths[0].get_text()) - 1

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
							'Host': host,
							'Origin': 'http://' + host,
							'Referer': 'http://' + host + '/Edcard.htm?ID=' + idd,
							'Upgrade-Insecure-Requests': '1',
							'User-Agent': 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36'
						}

						page = requests.post("http://" + host + "/Edcard.htm", data=data, headers=headers, auth=('admin', '888888'))
						
						soup = BeautifulSoup(page.content, 'html.parser')
						h2 = soup.find_all('h2')
						print(h2)
						if len(h2) > 0:
							if h2[0].get_text().strip() == "Successfully!":
								done = True
								break



enable(hosts, name, card, year, month, day, hour, minute)