import { Injectable } from '@angular/core';
import {
  CanActivate,
  Router,
  ActivatedRouteSnapshot,
  RouterStateSnapshot
} from '@angular/router';
import { AuthService } from '../login/auth.service';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanActivate {
  constructor(private authService: AuthService, private router: Router) {}

  canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
    const currentUser = this.authService.currentUserValue;
    if (currentUser) {
      // authorised so return true
      return true;
    }

    //  if (currentUser) {
    //    // check if route is restricted by role
    //    console.log(route.data['roles'])
    //    if (
    //      route.data.roles &&
    //      route.data.roles.indexOf(currentUser.role) === -1
    //    ) {
    //      // role not authorised so redirect to home page
    //      this.router.navigate(['/']);
    //      return false;
    //    }

    //    // authorised so return true
    //    return true;
    //  }


    // not logged in so redirect to login page with the return url
    this.router.navigate(['/login'], { queryParams: { returnUrl: state.url } });
    return false;
  }

  // canActivate(): boolean {
  //   if (this.authService.loggedin()) {
  //     return true
  //   } else {
  //     this.router.navigate(['/login'])
  //   }
  //   return false
  // }
}
