import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders } from "@angular/common/http";

@Injectable({
  providedIn: "root",
})
export class MakeBookingService {
  private url = "/main/api/destination/time";

  private uri = "/main/api/buses";


  private destinationUrl = "/main/api/destinations";

  private busUrl = '/api/v1/btms/travel/secured/routes'

  constructor(private http: HttpClient) {}

  async getList(): Promise<any> {
    const url = `${this.busUrl}`;
    return await this.http.get(url).toPromise().catch(this.handleError);
    console.log(this.url);
  }

   async getsubRouteList(destination: any): Promise<any> {
    return await this.http.post(`${this.busUrl}`, destination).toPromise().catch(this.handleError)
   }

  async getBusList(id: number): Promise<any> {
    const url = `${this.uri}`;
    return await this.http
      .get(url + '/' + id)
      .toPromise()
      .catch(this.handleError);
  }

  async createDestination(destination: any ): Promise<any> {
    return await this.http.post(`${this.destinationUrl}`, destination).toPromise().catch(this.handleError)
  }

  async updateDestination(destination: any, ed_bus_route_id: number ): Promise<any> {
    return await this.http.put(`${this.destinationUrl}/${ed_bus_route_id}`, destination).toPromise().catch(this.handleError)
  }

  // handler for error in URL
  private handleError(error: any): Promise<any> {
    return Promise.reject(error.message || error);
  }
}
