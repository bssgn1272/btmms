import { Injectable } from "@angular/core";
import { Http } from "@angular/http";
import { HttpClient, HttpHeaders } from "@angular/common/http";
// import { IGroup } from './group';

@Injectable({
  providedIn: "root",
})
export class ReservationRequestsService {
  private url = "/main/api/reservations/requests";
  private urlh = "/main/api/reservations/requests/history";
  private urlaR = "/main/api/arreservations/requests";
  private urlhaR = "/main/api/arreservations/requests/history";

  constructor(private http: HttpClient) {}

  async getList(): Promise<any> {
    const url = `${this.url}`;
    return await this.http.get(url).toPromise().catch(this.handleError);
  }

  async getHistoryList(): Promise<any> {
    const url = `${this.urlh}`;
    return await this.http.get(url).toPromise().catch(this.handleError);
  }


  async getARList(): Promise<any> {
    const url = `${this.urlaR}`;
    return await this.http.get(url).toPromise().catch(this.handleError);
  }

  async getARHistoryList(): Promise<any> {
    const url = `${this.urlhaR}`;
    return await this.http.get(url).toPromise().catch(this.handleError);
  }

  async getRangeList(): Promise<any> {
    const url = `${this.url}`;
    return await this.http.get(url).toPromise().catch(this.handleError);
  }

  // handler for error in URL
  private handleError(error: any): Promise<any> {
    return Promise.reject(error.message || error);
  }
}
