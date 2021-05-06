import { Injectable } from '@angular/core';
import {User} from '../models';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../login/auth.service';
import {Observable} from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ViewMyChargesService {
  currentUser: User;

  private url = '/main/api/charges';

  constructor(private http: HttpClient, private authService: AuthService) {
    this.currentUser = this.authService.currentUserValue;
  }

   getList(): Observable<any> {
    const url = `${this.url}`;
    return this.http.get<any>(url);
  }


  private handleError(error: any): Promise<any> {
    return Promise.reject(error.message || error);
  }
}
