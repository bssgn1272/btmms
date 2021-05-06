import threading
import requests
import pymysql
import time
import xml.etree.ElementTree as ET
from requests.auth import HTTPBasicAuth

def boom():
    while True:
        hosts =[]
        db = pymysql.connect(host='127.0.0.1',
                            user='root',
                            password='',
                            db='accesscontrolapi',
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
            try:
                with db:
                    cur = db.cursor()
                    cur.execute("SELECT * FROM boomseqnos WHERE boom_id = %s", (host['id'],))
                    rows = cur.fetchall()
                    
                    for row in rows:
                        seqNo = int(row['seqno'])
                        seqNo += 1
                
                url = "http://%s:%s/device.cgi/events?action=getevent&roll-over-count=0&seq-number=%s&format=xml"%(host['ip'], host['port'], seqNo)
                r = requests.get(url, auth=HTTPBasicAuth(host['username'], host['password']))
                data = r.content
                if r.status_code == 200 or r.status_code == '200':
                    tree = ET.fromstring(data)
                    
                    for responseCode in tree.findall('Response-Code'):
                        print(host['name'] +': No new events: ' + responseCode)
                        continue

                    for event in tree.findall('Events'):
                        if(event.find('detail-1').text) == 0 or event.find('detail-1').text == '0':
                            continue
                        seqNo = int(event.find('seq-No').text)
                        print('Seq No:' + event.find('seq-No').text)
                        print('Date:' + event.find('date').text)
                        print('Time:' + event.find('time').text)
                        print('Event ID:' + event.find('event-id').text)
                        print('Card #:' + event.find('detail-1').text)
                        print()

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
            time.sleep(5)