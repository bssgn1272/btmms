import { Injectable } from '@angular/core';
import { Http } from '@angular/http';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { User } from 'app/models/user';
import { AuthService } from 'app/login/auth.service';
// import { IGroup } from './group';

@Injectable({
  providedIn: 'root'
})
export class ViewSlotsService {
  currentUser: User;

  private url = '/api/reservation/get';

  constructor(private http: HttpClient, private authService: AuthService) {
    this.currentUser = this.authService.currentUserValue;
  }

  async getList(id: number): Promise<any> {
    const url = `${this.url}/${id}`;
    return await this.http
      .get(url)
      .toPromise()
      .catch(this.handleError);
    console.log(this.currentUser.username);
  }

  // handler for error in URL
  private handleError(error: any): Promise<any> {
    return Promise.reject(error.message || error);
  }
}
