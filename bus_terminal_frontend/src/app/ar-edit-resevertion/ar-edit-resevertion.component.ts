import {Component, Inject, OnInit, Optional} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {HttpClient} from '@angular/common/http';
import {ActivatedRoute, Router} from '@angular/router';
import {MatSnackBar} from '@angular/material/snack-bar';
import {MakeBookingService} from '../make-booking/make-booking.service';
import {Location} from '@angular/common';
import {ViewSlotsService} from '../view-my-slots/view-slots.service';
import {SettingsService} from '../settings/settings.service';
import {SlotInteravlService} from '../settings/slot-interval.service';
import {ArMakeBookingService} from '../ar-make-booking/ar-make-booking.service';

@Component({
  selector: 'app-ar-edit-resevertion',
  templateUrl: './ar-edit-resevertion.component.html',
  styleUrls: ['./ar-edit-resevertion.component.scss']
})
export class ArEditResevertionComponent implements OnInit {
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
  selectedRole: any;
  selectedR: any[] = [];
  constructor(
      public dialogRef: MatDialogRef<ArEditResevertionComponent>,
      @Optional() @Inject(MAT_DIALOG_DATA) public data: any,
      private httpClient: HttpClient,
      private routes: ActivatedRoute,
      private router: Router,
      private _snackBar: MatSnackBar,
      private makeBookingService: ArMakeBookingService,
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
    this.userItems = this.getFromLocalStrorage();
    this._id = this.userItems.ID;
    this.user = this.userItems.username;

    this.slot_one = this.data.row.slot_one;

    this.bookingForm.get('slot').setValue(this.data.row.slot)

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

    // sources
    await this.loadSources();
    // get return url from route parameters or default to '/'
    this.returnUrl =
        this.routes.snapshot.queryParams['returnUrl'] || '/dashboard';

    this.loadBuses();

    console.log('DATAAAAAA>>>>', this.data.row);
  }

  // fetch routes
  async loadSources() {
    this.makeBookingService.getList().then((res) => {
      console.log('SOURCES>>>>>', res);

      this.sources = res.data;
    });
  }

  // fetch buses
  async loadBuses() {
    await this.makeBookingService.getBusList(this._id).then((res) => {
      this.busesFilter = res.data;
      console.log('=========================>>>', this.busesFilter);

      this.reservationService.arGetList(this._id).then((rese) => {
        this.reservedBus = rese.data;
        const arOperatingDate = (new Date(sessionStorage.getItem('arOperatingDate'))).toISOString().split('T')[0];

        this.buses = this.busesFilter.filter(
            (o) =>
                !this.reservedBus.find(
                    (o2) => o.ID === o2.bus_id && o2.reservation_status === 'A' && o2.reserved_time.split('T')[0] === arOperatingDate
                )
        );


        this.selectedR = rese.data;

        this.selectedRole = this.selectedR.filter((x) => x.time === this.data.row.time &&
            x.reserved_time.split('T')[0] === this.data.row.reserved_date.split('T')[0] && x.slot === this.data.row.slot)[0];

        console.log('BUSSEShhh>>>>>', this.selectedR, this.selectedRole, this.data.row.reserved_date)

        this.bookingForm.get('route').setValue(this.selectedRole.end_route)
        this.bookingForm.get('bus').setValue(this.selectedRole.bus_id)
      });

      console.log('Buses>>>>>', this.bookingForm.controls.bus.value);
    });
  }

  get f() {
    return this.bookingForm.controls;
  }


  editReservation() {
    this.submitted = true;

    // stop here if form is invalid
    if (this.bookingForm.invalid) {
      return;
    }
    console.log(this.f.route.value);


    this.httpClient
        .put('/api/approve/arreservations/requests/' + this.selectedRole.res_uuid, {route: Number(this.f.route.value), bus_id: Number(this.f.bus.value)})
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

    // this.slot = '';
    // this.route = '';
  }

  close() {
    this.dialogRef.close();
  }

}
