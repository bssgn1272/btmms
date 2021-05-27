import { Injectable } from "@angular/core";
import { Http } from "@angular/http";
import { HttpClient, HttpHeaders } from "@angular/common/http";
import { User } from "app/models/user";
import { AuthService } from "app/login/auth.service";
// import { IGroup } from './group';

@Injectable({
  providedIn: "root",
})
export class ViewSlotsService {
  currentUser: User;

  private url = "/main/api/reservation/get";
  private urlh = "/main/api/reservation/history/get";
  private arUrl = "/main/api/arreservation/get/active";
  private arUrlh = "/main/api/arreservation/get/active/history";
  //private arUrlh = "/main/api/arreservation/history/get";
  private subUrl = "/main/api/destinations";

  constructor(private http: HttpClient, private authService: AuthService) {
    this.currentUser = this.authService.currentUserValue;
  }

  async getList(id: number): Promise<any> {
    const url = `${this.url}/${id}`;
    return await this.http.get(url).toPromise().catch(this.handleError);
  }

  async getHistoryList(): Promise<any> {
    const url = `${this.arUrlh}`;
    return await this.http.get(url).toPromise().catch(this.handleError);
  }

  /* async getHistoryList(id): Promise<any> {
    const url = `${this.urlh}/${id}`;
    return await this.http.get(url).toPromise().catch(this.handleError);
  } */

  async arGetList(id: number): Promise<any> {
    const url = `${this.arUrl}/${id}`;
    return await this.http.get(url).toPromise().catch(this.handleError);
  }

  async arGetListAll(): Promise<any> {
    const url = `${this.arUrl}`;
    return await this.http.get(url).toPromise().catch(this.handleError);
  }

  async arGetHistoryList(id): Promise<any> {
    const url = `${this.arUrlh}/${id}`;
    return await this.http.get(url).toPromise().catch(this.handleError);
  }

  async getROutes(): Promise<any> {
    return  await this.http.get(`${this.subUrl}`).toPromise().catch(this.handleError)
  }

  // handler for error in URL
  private handleError(error: any): Promise<any> {
    return Promise.reject(error.message || error);
  }
}
