import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';

declare const $: any;
declare interface RouteInfo {
  path: string;
  title: string;
  icon: string;
  class: string;
}
export const ROUTES: RouteInfo[] = [
  {
    path: '/dashboard',
    title: 'Dashboard',
    icon: 'dashboard',
    class: ''
  },
  {
    path: '/veiw-slot',
    title: 'View Slots',
    icon: 'view_list',
    class: ''
  },
  // {
  //   path: '/veiw-resavations-requests',
  //   title: 'View Requests',
  //   icon: 'view_module',
  //   class: ''
  // }
];


export const ROUTESTWO: RouteInfo[] = [
  // {
  //   path: "/dashboard",
  //   title: "Dashboard",
  //   icon: "dashboard",
  //   class: ""
  // },
  // {
  //   path: "/veiw-slot",
  //   title: "View Slots",
  //   icon: "view_list",
  //   class: ""
  // },
  {
    path: '/veiw-resavations-requests',
    title: 'View Requests',
    icon: 'view_module',
    class: ''
  }
];

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.css']
})
export class SidebarComponent implements OnInit {
  menuItems: any[];
  userItems: any;
  returnUrl: string;

  constructor(private route: ActivatedRoute, private router: Router) {}

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem('currentUser'));
    return users;
  }

  ngOnInit() {
    this.userItems = this.getFromLocalStrorage();
    const _role = this.userItems.account.role;
    if (_role === 'admin') {
      this.menuItems = ROUTESTWO.filter(menuItem => menuItem);
      // get return url from route parameters or default to '/'
    this.returnUrl =
      this.route.snapshot.queryParams['returnUrl'] ||
      '/veiw-resavations-requests';
      this.router.navigate([this.returnUrl]);
    } else if (_role === 'operator') {
      this.menuItems = ROUTES.filter(menuItem => menuItem);
       // get return url from route parameters or default to '/'
    this.returnUrl =
      this.route.snapshot.queryParams['returnUrl'] ||
      '/dashboard';
      this.router.navigate([this.returnUrl]);
    }
  }
  isMobileMenu() {
    if ($(window).width() > 991) {
      return false;
    }
    return true;
  }
}
