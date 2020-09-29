import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class OpenSlotsService {
  private url = '/api/slots/getbydate/';
  private urlTest = '/api/slots/get';
  private arUrl = '/api/arslots/getbydate/';

  constructor(private http: HttpClient) {}

  async getList(date: any = new String('1970-01-01')): Promise<any> {
    let today = new Date();
    today.setDate(today.getDate() + 1);
    if (!(date == '1970-01-01')) {
      today = new Date(date);
    }
    let dt = this.convertDate(today);
    const url = `${this.url + dt}`;
    return await this.http
      .get(url)
      .toPromise()
      .catch(this.handleError);
  }

  async arGetList(date: any = new String('1970-01-01')): Promise<any> {
    let today = new Date();
    today.setDate(today.getDate() + 1);
    if (!(date == '1970-01-01')) {
      today = new Date(date);
    }
    let dt = this.convertDate(today);
    const url = `${this.arUrl + dt}`;
    return await this.http
      .get(url)
      .toPromise()
      .catch(this.handleError);
  }

  // testSlots() {
  //   return this.http
  //       .get<any>(this.urlTest)
  // }

  private convertDate(today: Date): any {
    const dd = today.getDate();
    const mm = today.getMonth() + 1;
    const yyyy = today.getFullYear();
    let day = '' + dd;
    let month = '' + mm;

    if (dd < 10) {
        day = '0' + dd;
    }

    if (mm < 10) {
        month = '0' + mm;
    }
    const dt = yyyy + '-' + month + '-' + day + ' ' + '20:00:00';
    return dt;
  }

  // handler for error in URL
  private handleError(error: any): Promise<any> {
    return Promise.reject(error.message || error);
  }
}
