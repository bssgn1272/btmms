import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from "@angular/common/http";

@Injectable({
  providedIn: "root",
})
export class SettingsService {
  private url = "/api/destination/time";
  private urlb = "/api/day";
  private urlM = "/api/workflow";
  private urlT = "/api/penalty/time";

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

  async getDUeTimes(): Promise<any> {
    const urlT = `${this.urlT}`;
    return await this.http.get(urlT).toPromise().catch(this.handleError);
  }
  // handler for error in URL
  private handleError(error: any): Promise<any> {
    return Promise.reject(error.message || error);
  }
}
