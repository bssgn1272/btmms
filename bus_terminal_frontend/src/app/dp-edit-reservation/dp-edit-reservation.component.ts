import {Component, Inject, OnInit, Optional} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {HttpClient, HttpParams} from '@angular/common/http';
import {ActivatedRoute, Router} from '@angular/router';
import {MakeBookingService} from '../make-booking/make-booking.service';
import {Location} from '@angular/common';
import {ViewSlotsService} from '../view-my-slots/view-slots.service';
import {SettingsService} from '../settings/settings.service';
import {SlotInteravlService} from '../settings/slot-interval.service';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {MatSnackBar} from '@angular/material/snack-bar';
import { v4 } from 'uuid';

@Component({
  selector: 'app-dp-edit-reservation',
  templateUrl: './dp-edit-reservation.component.html',
  styleUrls: ['./dp-edit-reservation.component.scss']
})
export class DpEditReservationComponent implements OnInit {

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

  reservedBus = [];
  bookingR: any;
  selectedR: any[] = [];
  selectedRole: any;
   newRoutes: any;
  constructor(
      public dialogRef: MatDialogRef<DpEditReservationComponent>,
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
      ed_bus_route_id: ['', Validators.required],
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
    this.userItems = this.getFromLocalStrorage();
    this._id = this.userItems.ID;
    this.user = this.userItems.username;

    console.log('DADADADADA>>>>>', this.data.row)

    this.bookingForm.get('slot').setValue(this.data.row.slot)

    this.user_id = this.data.row.id;
    this.time = this.data.row.time;
    // this.reserved_time = this.data.row.reserved_time;

    // destinations
    await this.loadDestinations();
    // get return url from route parameters or default to '/'
    this.returnUrl =
        this.routes.snapshot.queryParams['returnUrl'] || '/dashboard';

    this.loadBuses();

    console.log('BOOKING DETAILS', this.data.row);
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

    await this.makeBookingService.getBusList(this._id).then((res) => {
      this.busesFilter = res.data;


      this.reservationService.getList(this._id).then((rese) => {
        this.reservedBus = rese.data;
        const operatingDate = (new Date(sessionStorage.getItem('operatingDate'))).toISOString().split('T')[0];

        this.buses = this.busesFilter.filter(
          (o) =>
            !this.reservedBus.find(
              (o2) => o.ID === o2.bus_id && o2.reservation_status === 'A' && o2.reserved_time.split('T')[0] === operatingDate
            )
        );

        // this.buses = this.busesFilter;
        //     .filter(
        //     (o) =>
        //         !this.reservedBus.find(
        //             (o2) => o.id === o2.bus_id && o2.status === 'A'
        //         )
        // );

        this.selectedR = rese.data;

        this.selectedRole = this.selectedR.filter((x) => x.time === this.data.row.time &&
            x.reserved_time.split('T')[0] === this.data.row.reserved_date.split('T')[0] && x.slot === this.data.row.slot)[0];
        console.log('==============SELECTED===========>>>', this.selectedRole);

        this.bookingForm.get('ed_bus_route_id').setValue(this.selectedRole.end_route)
        this.bookingForm.get('bus').setValue(this.selectedRole.bus_id)

        // this.loadReservations(this.slot);
      });

      this.slotInteravlService.getList().then((slots) => {
        this.newSlots = slots.data.filter((x) => x.slot_one === this.data.row.slot_one || x.slot_two === this.data.row.slot_two ||
            x.slot_three === this.data.row.slot_three || x.slot_four === this.data.row.slot_four ||
            x.slot_five === this.data.row.slot_five || x.slot_six === this.data.row.slot_six ||
            x.slot_seven === this.data.row.slot_seven || x.slot_eight === this.data.row.slot_eight ||
            x.slot_nine === this.data.row.slot_nine && x.time === this.data.row.time &&
            x.reservation_time === this.data.row.reservation_time);
      });
      console.log(this.buses);
    });
  }


  getSubRoutes(routing: any) {

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
        ID: this.selectedRole.ed_bus_route_id,
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

  async getCharge(event) {
    await this.settings.getBookingCharge(event.value).then((res) => {
      this.charge = res.data[0];
    });
  }

  get f() {
    return this.bookingForm.controls;
  }

  async editReservation() {
    this.submitted = true;

    // stop here if form is invalid
    if (this.bookingForm.invalid) {
      return;
    }
    // console.log(this.f.route.value);

    // await this.makeBookingService.updateDestination(this.newRoutes, this.selectedRole.ed_bus_route_id).then((resUpdate) => {
    //   console.log('UPDATED ROUTE>>>>>>>>', resUpdate)

      this.httpClient
          .put('/main/api/approve/reservations/requests/' + this.selectedRole.res_uuid, {bus_id: Number(this.f.bus.value)})
          .subscribe(
              () => {
                // this._location.back();
                this._snackBar.open('Successfully Updated', null, {
                  duration: 1000,
                  horizontalPosition: 'center',
                  panelClass: ['blue-snackbar'],
                  verticalPosition: 'top',
                });
                this.dialogRef.close();
              },
              () => {
                this._snackBar.open('Failed', null, {
                  duration: 2000,
                  horizontalPosition: 'center',
                  panelClass: ['background-red'],
                  verticalPosition: 'top',
                });
                this.dialogRef.close();
              }
          );


    // })




    // this.slot = '';
    // this.route = '';

  }

  close() {
    this.dialogRef.close();
  }

}
