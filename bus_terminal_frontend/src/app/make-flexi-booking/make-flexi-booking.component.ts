import {Component, Inject, OnInit, Optional} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {HttpClient, HttpParams} from '@angular/common/http';
import {ActivatedRoute, Router} from '@angular/router';
import {ArMakeBookingService} from '../ar-make-booking/ar-make-booking.service';
import {Location} from '@angular/common';
import {ViewSlotsService} from '../view-my-slots/view-slots.service';
import {MakeBookingService} from '../make-booking/make-booking.service';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {MatSnackBar} from '@angular/material/snack-bar';
import { v4 } from 'uuid';

export interface Slot {
  value: string;
  viewValue: string;
}

export interface Destination {
  town_name: string;
  time_of_day: string;
}

@Component({
  selector: 'app-make-flexi-booking',
  templateUrl: './make-flexi-booking.component.html',
  styleUrls: ['./make-flexi-booking.component.scss']
})
export class MakeFlexiBookingComponent implements OnInit {
    flexi_operators: any[] = [];
  // Booking formGroup
  bookingForm: FormGroup;
  // slot = '';
  status = 'A';
  // route = '';
  time = '';
  user_id = 0;
  user = '';
  // reserved_time = '';

  open = false;
  closed = false;

  slot_one = '';
  slot_two = '';
  slot_three = '';
  slot_four = '';
  slot_five = '';
  slot_six = '';
  slot_seven = '';
  slot_eight = '';
  slot_nine = '';
  returnUrl: '';
  userItems: any;
  _id: any;
  public sources: [];
  submitted = false;
  buses: any[] = [];
  busesFilter = [];

  reservedBus = [];
  newRoutes: any;

  constructor(
      public dialogRef: MatDialogRef<MakeFlexiBookingComponent>,
      @Optional() @Inject(MAT_DIALOG_DATA) public data: any,
      private httpClient: HttpClient,
      private routes: ActivatedRoute,
      private router: Router,
      private _snackBar: MatSnackBar,
      private makeBookingService: ArMakeBookingService,
      private _formBuilder: FormBuilder,
      private _location: Location,
      private reservationService: ViewSlotsService,
      private makeDepBookingService: MakeBookingService,
  ) {
    this.bookingForm = this._formBuilder.group({
      flexi_operator: ['', Validators.required],
      slot: ['', Validators.required],
      route: ['', Validators.required],
      bus_detail: [''],
    });
  }

  /* Handle form errors in Angular 8 */
  public errorHandling = (control: string, error: string) => {
    return this.bookingForm.controls[control].hasError(error);
  };

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem('currentUser'));
    return users;
  }

  async ngOnInit() {
    this.userItems = this.getFromLocalStrorage();
    this._id = this.userItems.ID;
    this.user = this.userItems.username;

    this.slot_one = this.data.row.slot_one;

    if (this.slot_one !== 'open') {
      this.closed = true;
      this.open = false;
    }

    if (this.slot_one === 'open') {
      this.open = true;
      this.closed = false;
    }

    this.slot_two = this.data.row.slot_two;
    if (this.slot_two !== 'open') {
      this.closed = true;
      this.open = false;
    }

    if (this.slot_two === 'open') {
      this.open = true;
      this.closed = false;
    }

    this.slot_three = this.data.row.slot_three;
    if (this.slot_three !== 'open') {
      this.closed = true;
      this.open = false;
    }

    if (this.slot_three === 'open') {
      this.open = true;
      this.closed = false;
    }

    this.slot_four = this.data.row.slot_four;
    if (this.slot_four !== 'open') {
      this.closed = true;
      this.open = false;
    }

    if (this.slot_four === 'open') {
      this.open = true;
      this.closed = false;
    }

    this.slot_five = this.data.row.slot_five;
    if (this.slot_five !== 'open') {
      this.closed = true;
      this.open = false;
    }

    if (this.slot_five === 'open') {
      this.open = true;
      this.closed = false;
    }

    this.slot_six = this.data.row.slot_six;
    if (this.slot_six !== 'open') {
      this.closed = true;
      this.open = false;
    }

    if (this.slot_six === 'open') {
      this.open = true;
      this.closed = false;
    }

    this.slot_seven = this.data.row.slot_seven;
    if (this.slot_seven !== 'open') {
      this.closed = true;
      this.open = false;
    }

    if (this.slot_seven === 'open') {
      this.open = true;
      this.closed = false;
    }

    this.slot_eight = this.data.row.slot_eight;
    if (this.slot_eight !== 'open') {
      this.closed = true;
      this.open = false;
    }

    if (this.slot_eight === 'open') {
      this.open = true;
      this.closed = false;
    }

    this.slot_nine = this.data.row.slot_nine;
    if (this.slot_nine !== 'open') {
      this.closed = true;
      this.open = false;
    }

    if (this.slot_nine === 'open') {
      this.open = true;
      this.closed = false;
    }

    this.user_id = this.data.row.id;
    this.time = this.data.row.time;
    // this.reserved_time = this.data.row.reserved_time;

    this.makeBookingService.getUsersList().then((res) => {
      this.flexi_operators = res.data.filter((x) => x.role === 'FOP');
    })

    // sources
    await this.loadSources();
    // get return url from route parameters or default to '/'
    this.returnUrl =
        this.routes.snapshot.queryParams['returnUrl'] || '/flexi-booking';
  }

  async loadSources() {
    this.makeDepBookingService.getList().then((res) => {
      console.log('SOURCES>>>>>', res);

      this.sources = res.travel_routes;
    });
  }

  get f() {
    return this.bookingForm.controls;
  }


  save() {
    this.submitted = true;

    // stop here if form is invalid
    if (this.bookingForm.invalid) {
      return;
    }
    console.log(this.f.route.value);

    this.status = 'p';
    let message = 'Slot Successfully Reserved';
    this.time = this.data.row.time;
    const reservation_time = (new Date(sessionStorage.getItem('arOperatingDate'))).toISOString().split('.')[0] + 'Z';
    console.log(reservation_time);
    if (this.data.row.slot_one === this.user || this.data.row.slot_two === this.user || this.data.row.slot_three === this.user ||
        this.data.row.slot_four === this.user || this.data.row.slot_five === this.user || this.data.row.slot_six === this.user ||
        this.data.row.slot_seven === this.user || this.data.row.slot_eight === this.user || this.data.row.slot_nine === this.user) {
      this.status = 'A'
    }

    const reserv = {
      slot: this.f.slot.value,
      reservation_status: this.status,
      ed_bus_route_id: this.f.route.value,
      user_id: this.f.flexi_operator.value.ID,
      res_uuid: v4(),
      time: this.data.row.time,
      reserved_time: reservation_time, // this.data.row.reservation_time
      bus_detail: this.f.bus_detail.value,
    }

    this.makeDepBookingService.createDestination(this.newRoutes).then( (resRoute) => {
      console.log('RESULT>>>>>', resRoute)

      const routeID = resRoute.routes.ID

      reserv.ed_bus_route_id = routeID

      this.httpClient
          .post('/main/api/arreservation/requests/create', reserv)
          .subscribe(
              (data) => {
                console.log('Check Response>>>>>', data)
                if (this.status === 'P') {
                  message = 'Slot Reservation Pending Approval';
                }

                message = message + ', SLot Number: ' + this.f.slot.value + ' Reserved Time: ' + this.data.row.time

                let body = new HttpParams();
                body = body.set('receiver', this.f.flexi_operator.value.mobile);
                body = body.set('msg', message);
                this.httpClient.get('/api/sms', {params: body}).subscribe(
                    (data) => {
                    },
                    (error) => {
                    }
                );

                const subject = 'Reservation';
                let bodyc = new HttpParams();
                bodyc = bodyc.set('email', this.f.flexi_operator.value.email);
                bodyc = bodyc.set('user', this.f.flexi_operator.value.username);
                bodyc = bodyc.set('subject', subject);
                bodyc = bodyc.set('msg', message);
                this.httpClient.get('/api/email', {params: bodyc}).subscribe(
                    (data) => {
                    },
                    (error) => {
                    }
                );
                // this._location.back();
                this._snackBar.open(message, null, {
                  duration: 1000,
                  horizontalPosition: 'center',
                  panelClass: ['blue-snackbar'],
                  verticalPosition: 'top',
                });
                this.dialogRef.close();
              },
              (error) => {
                this._snackBar.open('Failed', null, {
                  duration: 2000,
                  horizontalPosition: 'center',
                  panelClass: ['background-red'],
                  verticalPosition: 'top',
                });
                this.dialogRef.close();
              }
          );
    });
  }

  async getSubRoutes(routing: any) {

    // const headers_object = new HttpHeaders();
    // headers_object.append('Content-Type', 'application/json');
    // headers_object.append('Authorization', 'Basic ' + btoa('manager:JJ8DJ7S66DMA5'));
    //
    // const httpOptions = {
    //   headers: headers_object
    // };
    const payload = {
      'auth': {
        'username': 'manager',
        'service_token': 'JJ8DJ7S66DMA5'
      },
      'payload': {
        'route_code': routing.route_code
      }
    }

    this.makeDepBookingService.getsubRouteList(payload).then((res) => {
      this.newRoutes = {
        end_route: res.travel_route.end_route,
        start_route: res.travel_route.start_route,
        route_code: res.travel_route.route_code,
        route_fare: res.travel_route.route_fare,
        route_name: res.travel_route.route_name,
        route_uuid: res.travel_route.route_uuid,
        source_state: res.travel_route.source_state,
        Parent: res.travel_route.Parent,
        sub_routes: res.travel_route.sub_routes
      }

      console.log('TEST !@!@>>>', res, routing.route_code)
    })
  }

  close() {
    this.dialogRef.close();
  }

}
