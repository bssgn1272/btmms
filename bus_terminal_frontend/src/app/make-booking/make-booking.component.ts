import { Component, OnInit, Optional, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA, MatSnackBar } from '@angular/material';
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute, Router } from '@angular/router';
import { MakeBookingService } from './make-booking.service';
import { FormGroup, Validators, FormBuilder } from '@angular/forms';
import { Location } from '@angular/common';

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
  styleUrls: ['./make-booking.component.scss']
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
  returnUrl: '';
  userItems: any;
  _id: any;
  public destinations: [];
  submitted = false;

  constructor(
    public dialogRef: MatDialogRef<MakeBookingComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: any,
    private httpClient: HttpClient,
    private routes: ActivatedRoute,
    private router: Router,
    private _snackBar: MatSnackBar,
    private makeBookingService: MakeBookingService,
    private _formBuilder: FormBuilder,
    private _location: Location
  ) {
    this.bookingForm = this._formBuilder.group({
      slot: ['', Validators.required],
      route: ['', Validators.required]
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

    this.user_id = this.data.row.id;
    this.time = this.data.row.time;
    // this.reserved_time = this.data.row.reserved_time;

    // destinations
    await this.loadDestinations();
    // get return url from route parameters or default to '/'
    this.returnUrl =
      this.routes.snapshot.queryParams['returnUrl'] || '/dashboard';
    console.log(this.time);
  }

  // fetch groups
  async loadDestinations() {
    this.makeBookingService.getList().then(res => {
      this.destinations = res.data;
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

    this.status = 'A';
    this.time = this.data.row.time;

    this.httpClient
      .post('/api/reservation/requests/create', {
        slot: this.f.slot.value,
        status: this.status,
        route: this.f.route.value,
        user_id: this._id,
        time: this.data.row.time,
        reserved_time: this.data.row.reservation_time
      })
      .subscribe(
        data => {
          this._location.back();
          this._snackBar.open('Successfully Created', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );

    if (this.f.slot.value === 'slot_one') {
      this.httpClient
        .put('/api/slots/close', {
          time: this.time,
          slot_one: this.user
        })
        .toPromise();
    }
    if (this.f.slot.value === 'slot_two') {
      this.httpClient
        .put('/api/slots/close', {
          time: this.time,
          slot_two: this.user
        })
        .toPromise();
    }
    if (this.f.slot.value === 'slot_three') {
      this.httpClient
        .put('/api/slots/close', {
          time: this.time,
          slot_three: this.user
        })
        .toPromise();
    }
    if (this.f.slot.value === 'slot_four') {
      this.httpClient
        .put('/api/slots/close', {
          time: this.time,
          slot_four: this.user
        })
        .toPromise();
    }
    if (this.f.slot.value === 'slot_five') {
      this.httpClient
        .put('/api/slots/close', {
          time: this.time,
          slot_five: this.user
        })
        .toPromise();
    }

    // this.slot = '';
    // this.route = '';
    this.dialogRef.close();
  }

  close() {
    this.dialogRef.close();
  }
}
