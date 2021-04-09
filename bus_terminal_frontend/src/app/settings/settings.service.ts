import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from "@angular/common/http";
import {Observable} from 'rxjs';

@Injectable({
  providedIn: "root",
})
export class SettingsService {
  private url = "/main/api/destination/time";
  private urlb = "/main/api/day";
  private urlM = "/main/api/workflow";
  private urlT = "/main/api/options/get";
  private urlC = "/main/api/penalty/charge";
  private urlB = "/main/api/slots/charge";
  private urlP = "/main/api/penalty/time";
  private urlCP = "/main/api/auth/changepassword";
  private urlAcc = "/main/api/access/permissions/";
  private urlUsr = "/main/api/users";

  constructor(private http: HttpClient) {}

  async getList(): Promise<any> {
    const url = `${this.url}`;
    return await this.http.get(url).toPromise().catch(this.handleError);
  }

  async getDays(): Promise<any> {
    const urlb = `${this.urlb}`;
    return await this.http.get(urlb).toPromise().catch(this.handleError);
  }

  async getModes(): Promise<any> {
    const urlM = `${this.urlM}`;
    return await this.http.get(urlM).toPromise().catch(this.handleError);
  }

  updateMode(id: any, status: any): any {
    return this.http.put("/main/api/workflow/" + id, status).toPromise();
  }

  updateDueTime(id: any, status: any): any {
    return this.http.put("/main/api/penalty/time/" + id, status).toPromise();
  }

  async getOptions(): Promise<any> {
    const urlT = `${this.urlT}`;
    return await this.http.get(urlT).toPromise().catch(this.handleError);
  }

  async getLateCancellationCharge(id: any): Promise<any> {
    const urlC = `${this.urlC}/${id}`;
    return await this.http.get(urlC).toPromise().catch(this.handleError);
  }

  async getBookingCharge(id: any): Promise<any> {
    const urlB = `${this.urlB}/${id}`;
    return await this.http.get(urlB).toPromise().catch(this.handleError);
  }

  async getDueTimes(): Promise<any> {
    const urlP = `${this.urlP}`;
    return await this.http.get(urlP).toPromise().catch(this.handleError);
  }

  async changePassword(username: string, password: string): Promise<any> {
    const urlCP = `${this.urlCP}`;
    const jsonStr = `{"username":"${username}","password":"${password}"}`;
    return await this.http.post(urlCP, jsonStr).toPromise().catch(this.handleError);
  }


  updatePermission(id: any, status: any): Observable<any> {
    return this.http.put<any>(`${this.urlAcc}` + id, status);
  }


  async getUsers(): Promise<any> {
    const url = `${this.urlUsr}`;
    return await this.http.get(url).toPromise().catch(this.handleError);
  }

  // handler for error in URL
  private handleError(error: any): Promise<any> {
    return Promise.reject(error.message || error);
  }
}
