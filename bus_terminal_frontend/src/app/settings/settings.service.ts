import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from "@angular/common/http";

@Injectable({
  providedIn: "root",
})
export class SettingsService {
  private url = "/api/destination/time";
  private urlb = "/api/day";
  private urlM = "/api/workflow";
  private urlT = "/api/options/get";
  private urlC = "/api/penalty/charge";
  private urlB = "/api/slots/charge";
  private urlP = "/api/penalty/time";
  private urlCP = "/api/auth/changepassword";

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
    return this.http.put("/api/workflow/" + id, status).toPromise();
  }

  updateDueTime(id: any, status: any): any {
    return this.http.put("/api/penalty/time/" + id, status).toPromise();
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

  // handler for error in URL
  private handleError(error: any): Promise<any> {
    return Promise.reject(error.message || error);
  }
}
