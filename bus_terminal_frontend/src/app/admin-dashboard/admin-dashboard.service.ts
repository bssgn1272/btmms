import { Injectable } from '@angular/core';
import { Http } from '@angular/http';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import * as reque from './admin-dashboard';
// import { IGroup } from './group';

@Injectable({
  providedIn: 'root'
})
export class AdminDashboardService {
  public req1s: reque.Req1[] = [];
  public requ1s: any[];

  constructor(private http: HttpClient) {}

  // fetch Slot One Requests

  async loadSlotOneFive() {
    this.req1s = await this.http
      .get<any[]>('/api/reservations/requests/slot_one/five')
      .toPromise();

    this.requ1s = this.req1s.data;
  }

  // private url = '/api/reservations/requests/slot_one/five';

  // constructor(private http: HttpClient) {}

  // async getList(): Promise<any> {
  //   const url = `${this.url}`;
  //   return await this.http
  //     .get(url)
  //     .toPromise()
  //     .catch(this.handleError);
  // }

  // // handler for error in URL
  // private handleError(error: any): Promise<any> {
  //   return Promise.reject(error.message || error);
  // }
}
