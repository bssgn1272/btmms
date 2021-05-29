import { DatePipe } from '@angular/common';
import {Component, OnInit, Pipe, PipeTransform, SecurityContext} from '@angular/core';
import { SafeResourceUrl, DomSanitizer, SafeUrl } from '@angular/platform-browser';

@Pipe({ name: 'safe' })
export class SafePipe implements PipeTransform {
  constructor(private sanitizer: DomSanitizer) { }
  transform(url) {
    return this.sanitizer.bypassSecurityTrustUrl(url);
    //return this.sanitizer.sanitize(SecurityContext.URL, url);
  }
}

@Component({
  selector: 'app-reports',
  templateUrl: './reports.component.html',
  styleUrls: ['./reports.component.scss']
})
export class ReportsComponent implements OnInit {
  url1: any;
  url2: any;
  url3: any;
  url4: any;
  url5: any;
  url6: any;
  url7: any;
  userItems: any;

  constructor(public sanitizer: DomSanitizer) { }
  

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem('currentUser'));
    return users;
  }

  ngOnInit() {
    const today = new Date();
    const date = this.formatDate(today);
    this.userItems = this.getFromLocalStrorage();
    const _id = this.userItems.ID;

    //this.url1 = this.sanitizer.bypassSecurityTrustResourceUrl("http://10.70.1.4:9080/pentaho/api/repos/%3Apublic%3ASteel%20Wheels%3AReports%3AInventory%20List%20(report).prpt/viewer");
    this.url1 = "http://10.70.1.4:9080/pentaho/api/repos/%3Ahome%3Aadmin%3Abtmms%3Aeyed%3AManifest.prpt/viewer?userid=admin&password=password&departure_date=" + date + "&op_id=" + _id;
    this.url2 = "http://10.70.1.4:9080/pentaho/api/repos/%3Ahome%3Aadmin%3Abtmms%3Aeyed%3ATicketStatus.prpt/viewer?userid=admin&password=password&departure_date=" + date + "&op_id=" + _id;
    this.url3 = "http://10.70.1.4:9080/pentaho/api/repos/%3Ahome%3Aadmin%3Abtmms%3Aeyed%3Aticketsales.prpt/viewer?userid=admin&password=password&departure_date=" + date + "&op_id=" + _id;
    this.url4 = "http://10.70.1.4:9080/pentaho/api/repos/%3Ahome%3Aadmin%3Abtmms%3Aeyed%3Abybus.prpt/viewer?userid=admin&password=password&departure_date=" + date + "&op_id=" + _id;
    this.url5 = "http://10.70.1.4:9080/pentaho/api/repos/%3Ahome%3Aadmin%3Abtmms%3Aeyed%3Adepartures_report.prpt/viewer?userid=admin&password=password&departure_date=" + date + "&op_id=" + _id;
    this.url6 = "http://10.70.1.4:9080/pentaho/api/repos/%3Ahome%3Aadmin%3Abtmms%3Aeyed%3Aarrivals.prpt/viewer?userid=admin&password=password&departure_date=" + date + "&op_id=" + _id;
    this.url7 = "http://10.70.1.4:9080/pentaho/api/repos/%3Ahome%3Aadmin%3Abtmms%3Aeyed%3Acan_refund.prpt/viewer?userid=admin&password=password&departure_date=" + date + "&op_id=" + _id;
  }

  newWindow(){
    console.log(this.url1);
    window.open(this.url1, '_blank', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, height=600, width=1300');
  }

  private formatDate(date) {
    var d = new Date(),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
  }
}
