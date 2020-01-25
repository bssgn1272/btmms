import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class SlotInteravlService {
  private url = '/api/slots/get';

  constructor(private http: HttpClient) {}

  async getList(): Promise<any> {
    const url = `${this.url}`;
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
