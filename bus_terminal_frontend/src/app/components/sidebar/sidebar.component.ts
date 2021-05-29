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
    class: '',
  },
  {
    path: '/veiw-slot',
    title: 'View Slots',
    icon: 'view_list',
    class: '',
  },
  {
    path: '/view-my-penalties',
    title: 'View Penalties',
    icon: 'payment',
    class: ''
  },
  {
    path: '/view-my-charges',
    title: 'View Fees',
    icon: 'money',
    class: ''
  },
  {
    path: '/reports',
    title: 'reports',
    icon: 'analytics',
    class: ''
  }
];

export const ROUTESTWO: RouteInfo[] = [
  {
    path: '/veiw-resavations-requests',
    title: 'View Requests',
    icon: 'view_module',
    class: '',
  },
  {
    path: '/settings',
    title: 'Settings',
    icon: 'settings_applications',
    class: '',
  },
  {
    path: '/options',
    title: 'Options',
    icon: 'settings_applications',
    class: ''
  }
];

export const ROUTESTHREE: RouteInfo[] = [
  {
    path: '/veiw-resavations-requests',
    title: 'View Requests',
    icon: 'view_module',
    class: '',
  },
];

export const ROUTESFOUR: RouteInfo[] = [
  {
    path: '/flexi-booking',
    title: 'Flexi Booking',
    icon: 'view_module',
    class: '',
  },
  {
    path: '/veiw-slots',
    title: 'View Slots',
    icon: 'view_module',
    class: '',
  }
];

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.css'],
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
    const _role = this.userItems.role;
    console.log(_role);
    if (_role === 'ADMIN') {
      this.menuItems = ROUTESTWO.filter((menuItem) => menuItem);
      // get return url from route parameters or default to '/'
      this.returnUrl =
        this.route.snapshot.queryParams['returnUrl'] ||
        '/veiw-resavations-requests';
      this.router.navigate([this.returnUrl]);
    } else if (_role === 'BOP' || _role === 'FOP') {
      this.menuItems = ROUTES.filter((menuItem) => menuItem);
      // get return url from route parameters or default to '/'
      this.returnUrl =
        this.route.snapshot.queryParams['returnUrl'] || '/dashboard';
      this.router.navigate([this.returnUrl]);
    } else if (_role === 'CCOP') {
      this.menuItems = ROUTESFOUR.filter((menuItem) => menuItem);
      // get return url from route parameters or default to '/'
      this.returnUrl =
        this.route.snapshot.queryParams['returnUrl'] ||
        '/flexi-booking';
      this.router.navigate([this.returnUrl]);
    }else if (_role === 'TOP') {
      this.menuItems = ROUTESTHREE.filter((menuItem) => menuItem);
      // get return url from route parameters or default to '/'
      this.returnUrl =
          this.route.snapshot.queryParams['returnUrl'] ||
          '/veiw-resavations-requests';
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
