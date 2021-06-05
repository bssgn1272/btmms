import { Component, OnInit, Optional, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA, MatSnackBar } from '@angular/material';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { ActivatedRoute, Router } from '@angular/router';
import { MakeBookingService } from './make-booking.service';
import { FormGroup, Validators, FormBuilder } from '@angular/forms';
import { Location } from '@angular/common';
import { v4 } from 'uuid';
import { ViewSlotsService } from 'app/view-my-slots/view-slots.service';
import { SettingsService } from 'app/settings/settings.service';
import { SlotInteravlService } from '../settings/slot-interval.service';

export interface Slot {
  value: string;
  viewValue: string;
}

export interface Destination {
  town_name: string;
  time_of_day: string;
}

@Component({
  selector: 'app-make-booking',
  templateUrl: './make-booking.component.html',
  styleUrls: ['./make-booking.component.scss'],
})
export class MakeBookingComponent implements OnInit {
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
  charge: any;
  _id: any;
  public destinations: [];
  submitted = false;
  buses: any[] = [];
  busesFilter = [];
  newSlots: any[] = [];
  routeObj: any;
  newRoutes: any;

  reservedBus = [];
  constructor(
    public dialogRef: MatDialogRef<MakeBookingComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: any,
    private httpClient: HttpClient,
    private routes: ActivatedRoute,
    private router: Router,
    private _snackBar: MatSnackBar,
    private makeBookingService: MakeBookingService,
    private _formBuilder: FormBuilder,
    private _location: Location,
    private reservationService: ViewSlotsService,
    private settings: SettingsService,
    private slotInteravlService: SlotInteravlService
  ) {
    this.bookingForm = this._formBuilder.group({
      slot: ['', Validators.required],
      route: ['', Validators.required],
      bus: ['', Validators.required],
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
      console.log('USESESR>>>>>', this.getFromLocalStrorage())
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

    // destinations
    await this.loadDestinations();
    // get return url from route parameters or default to '/'
    this.returnUrl =
      this.routes.snapshot.queryParams['returnUrl'] || '/dashboard';

    this.loadBuses();

    console.log(this.loadBuses());
  }

  // fetch routes
  async loadDestinations() {
    this.makeBookingService.getList().then((res) => {
      console.log('DESTINATIONS>>>>>', res);

      this.destinations = res.travel_routes;
    });
  }



  // fetch buses
  async loadBuses() {
    console.log('=========================>>>', this._id);

    await this.makeBookingService.getBusList(this._id).then((res) => {
      this.busesFilter = res.data;
      console.log("Bus List>>>>", res.data);

      this.reservationService.getList(this._id).then((rese) => {
        this.reservedBus = rese.data;
        //console.log('BUSES>>>>>', rese.data)
        let operatingDate = (new Date(sessionStorage.getItem('operatingDate'))).toISOString().split('T')[0];

          if (res.data !== null) {
            this.buses = this.busesFilter
               .filter(
               (o) =>
                   !this.reservedBus.find(
                       (o2) => o.id === o2.bus_id && (o2.reservation_status === 'P' || o2.reservation_status === 'A') && o2.reserved_time.split('T')[0] === operatingDate
                   )
            );
          } else {
            this.buses = [];
          }
          console.log("Available buses>>> ", this.buses);
      });

        this.slotInteravlService.getList().then((slots) => {
            this.newSlots = slots.data;
        });
      console.log(this.buses);
    });
  }

  async getCharge(event) {
    await this.settings.getBookingCharge(event.value).then((res) => {
      this.charge = res.data[0];
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

    this.status = 'P';
    this.time = this.data.row.time;
    const reservation_time = (new Date(sessionStorage.getItem('operatingDate'))).toISOString().split('.')[0] + 'Z';
    console.log("Reservation Time: ", reservation_time);

    if (this.data.row.slot_one === this.user || this.data.row.slot_two === this.user || this.data.row.slot_three === this.user ||
        this.data.row.slot_four === this.user || this.data.row.slot_five === this.user || this.data.row.slot_six === this.user ||
        this.data.row.slot_seven === this.user || this.data.row.slot_eight === this.user || this.data.row.slot_nine === this.user) {
        this.status = 'P'
    }

    const reserv: any = {
      slot: this.f.slot.value,
      reservation_status: this.status,
      status: this.status,
      route: this.f.route.value,
      user_id: this._id,
      res_uuid: v4(),
      time: this.data.row.time,
      reserved_time: this.data.row.reservation_time , // reservation_time
      bus_id: this.f.bus.value,
    }

    // this.newRoutes.ed_reservation = reserv;

      let message = '';
    console.log('TEST ROUTES<><><><>', this.newRoutes)

    this.makeBookingService.createDestination(this.newRoutes).then( (resRoute) => {
      console.log('RESULT>>>>>', resRoute)

      const routeID = resRoute.routes.ID

      reserv.ed_bus_route_id = routeID

      if (this.status === 'P') {
        message = 'Departure Slot Reservation Pending Approval';
        message += '\nSlot: ' + this.f.slot.value;
        message += '\nDestination: ' + resRoute.routes.end_route;
        message += '\nTime: ' + this.data.row.reservation_time.split('T')[0] + ' ' + this.data.row.time;
      }

      this.httpClient
          .post('/main/api/reservation/requests/create', reserv)
          .subscribe(
              (data) => {
                console.log('Slot AND DATA>>>>', this.user, this.status, data)

                if (this.status === 'P') {
                  message = 'Dear operator,';
                  message += '\nYour booking for departure has been submitted for approval.'
                  message += '\nTime: ' + this.data.row.reservation_time.split('T')[0] + ' ' + this.data.row.time;
                  message += '\nDestination: ' + resRoute.routes.end_route;
                  message += '\nSlot: ' + this.f.slot.value;
                  message += '\nThank you.'
                }
                let body = new HttpParams();
                body = body.set('receiver', this.userItems.mobile);
                body = body.set('msg', message);
                this.httpClient.get('/main/api/sms', { params: body }).subscribe(
                    (data) => {},
                    (error) => {}
                );

                const subject = 'Departure Slot Reservation';
                let bodyc = new HttpParams();
                bodyc = bodyc.set('email', this.userItems.email);
                bodyc = bodyc.set('user', this.userItems.username);
                bodyc = bodyc.set('subject', subject);
                bodyc = bodyc.set('msg', message);
                this.httpClient.get('/main/api/email', { params: bodyc }).subscribe(
                    (data) => {},
                    (error) => {}
                );

                this.httpClient.get('/main/api/managers').subscribe(
                  (manager) => {
                    console.log('User Data>>> ',  manager['data']);
                    for(let i = 0; i < manager['data'].length; i++){
                      console.log('Zeroth element>>> ', manager['data'][i].email);
                      const subject = 'Departure Booking Pending Approval - ' + this.userItems.username;
                      let messagem = "Dear Manager,"
                      messagem += "\nA new application for departure has been submitted for your approval."
                      messagem += "\nBelow are the details:";
                      messagem += '\nTime: ' + this.data.row.reservation_time.split('T')[0] + ' ' + this.data.row.time;
                      messagem += '\nDestination: ' + resRoute.routes.end_route;
                      messagem += '\nSlot: ' + this.f.slot.value;
                      messagem += '\nThank you.'
                      messagem += '\nFrom LMBMC System Alerts'
                      let bodym = new HttpParams();
                      bodym = bodym.set('email', manager['data'][i].email);
                      bodym = bodym.set('user', manager['data'][i].username);
                      bodym = bodym.set('subject', subject);
                      bodym = bodym.set('msg', messagem);
                      this.httpClient.get('/main/api/email', { params: bodym }).subscribe(
                          (data) => {},
                          (error) => {}
                      );
                    }
                  },
                  (error) => {}
                );
                // this._location.back();
                this._snackBar.open('Successfully Submitted For Approval', null, {
                  duration: 3000,
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
        }
    )



    // this.slot = '';
    // this.route = '';

  }

  close() {
    this.dialogRef.close();
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

    this.makeBookingService.getsubRouteList(payload).then((res) => {
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
}
