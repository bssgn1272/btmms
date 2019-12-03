import { Injectable } from '@angular/core';
import { Http } from '@angular/http';
import { HttpClient, HttpHeaders } from '@angular/common/http';
// import { IGroup } from './group';

@Injectable({
  providedIn: 'root'
})
export class ViewSlotsService {

  id = 3;
  private url = '/api/reservation/get/';

  constructor(private http: HttpClient) {}

  async getList(): Promise<any> {
    const url = `${this.url + this.id}`;
    return await this.http
      .get(url)
      .toPromise()
      .catch(this.handleError);
  }

  // handler for error in URL
  private handleError(error: any): Promise<any> {
    return Promise.reject(error.message || error);
  }
}
