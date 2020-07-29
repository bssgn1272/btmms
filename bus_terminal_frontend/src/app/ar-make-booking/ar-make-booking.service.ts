import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from "@angular/common/http";

@Injectable({
  providedIn: "root",
})
export class ArMakeBookingService {
  private url = "/api/destination/time";

  private uri = "/api/buses";

  constructor(private http: HttpClient) {}

  async getList(): Promise<any> {
    const url = `${this.url}`;
    return await this.http.get(url).toPromise().catch(this.handleError);
    console.log(this.url);
  }

  async getBusList(id: string): Promise<any> {
    const url = `${this.uri}`;
    return await this.http
      .get(url + "/" + id)
      .toPromise()
      .catch(this.handleError);
  }

  // handler for error in URL
  private handleError(error: any): Promise<any> {
    return Promise.reject(error.message || error);
  }
}
