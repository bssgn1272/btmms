import { Injectable } from '@angular/core';
import {User} from '../models';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../login/auth.service';
import {Observable} from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ViewMyPenaltiesService {
  currentUser: User;

  private url = '/main/api/accumulated/penalties';

  constructor(private http: HttpClient, private authService: AuthService) {
    this.currentUser = this.authService.currentUserValue;
  }

   getList(id: number): Observable<any> {
    const url = `${this.url}/${id}`;
    return this.http.get<any>(url);
  }


  private handleError(error: any): Promise<any> {
    return Promise.reject(error.message || error);
  }
}
