import { Injectable } from '@angular/core';
import {User} from '../models';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../login/auth.service';
import {Observable} from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class OptionsService {
  currentUser: User;

  private url = '/main/api/options';

  constructor(private http: HttpClient, private authService: AuthService) {
    this.currentUser = this.authService.currentUserValue;
  }

   getOption(id: number): Observable<any> {
    const url = `${this.url}/${id}`;
    return this.http.get<any>(url);
  }

  getOptions(): Observable<any> {
    const url = this.url;
    return this.http.get<any>(url);
  }

  updateOption(id: number, option: any): Promise<any> {
    const url = `${this.url}/${id}`;
    return this.http.put<any>(url, option).toPromise().catch(this.handleError);
  }

  private handleError(error: any): Promise<any> {
    return Promise.reject(error.message || error);
  }
}
