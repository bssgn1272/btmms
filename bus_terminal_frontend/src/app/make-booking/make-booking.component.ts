import { Component, OnInit, Optional, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA, MatSnackBar } from '@angular/material';
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute, Router } from '@angular/router';

export interface Slot {
  value: string;
  viewValue: string;
}

@Component({
  selector: 'app-make-booking',
  templateUrl: './make-booking.component.html',
  styleUrls: ['./make-booking.component.scss']
})
export class MakeBookingComponent implements OnInit {
  slot = '';
  status = 'p';
  route = '';
  time = '';
  user_id = 0;
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

  constructor(
    public dialogRef: MatDialogRef<MakeBookingComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: any,
    private httpClient: HttpClient,
    private routes: ActivatedRoute,
    private router: Router,
    private _snackBar: MatSnackBar
  ) {}

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem('currentUser'));
    return users;
  }

  ngOnInit() {
    this.userItems = this.getFromLocalStrorage();
    this._id = this.userItems.ID;

    this.slot_one = this.data.row.slot_one;

    if (this.slot_one === 'closed' && this.data.row.time === '08:00') {
      this.closed = true;
      this.open = false;
    }

    if (this.slot_one === 'open') {
      this.open = true;
      this.closed = false;
    }

    this.slot_two = this.data.row.slot_two;
    if (this.slot_two === 'closed' && this.data.row.time === '08:00') {
      this.closed = true;
      this.open = false;
    }

    if (this.slot_two === 'open' && this.data.row.time === '08:00') {
      this.open = true;
      this.closed = false;
    }

    this.slot_three = this.data.row.slot_three;
    if (this.slot_three === 'closed') {
      this.closed = true;
      this.open = false;
    }

    if (this.slot_three === 'open') {
      this.open = true;
      this.closed = false;
    }

    this.slot_four = this.data.row.slot_four;
    if (this.slot_four === 'closed') {
      this.closed = true;
      this.open = false;
    }

    if (this.slot_four === 'open') {
      this.open = true;
      this.closed = false;
    }

    this.slot_five = this.data.row.slot_five;
    if (this.slot_five === 'closed') {
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

    // get return url from route parameters or default to '/'
    this.returnUrl = this.routes.snapshot.queryParams['returnUrl'] || '/';
    console.log(this.time);
  }

  save() {
    console.log(this.data.row.reservation_time);
    this.httpClient
      .post("/api/reservation/requests/create", {
        slot: this.slot,
        status: this.status,
        route: this.route,
        user_id: this._id,
        time: this.data.row.time,
        reserved_time: this.data.row.reservation_time
      })
      .subscribe(
        data => {
          this.router.navigate([this.returnUrl]);
          this._snackBar.open("Successfully Updated", null, {
            duration: 1000,
            horizontalPosition: "center",
            panelClass: ["blue-snackbar"],
            verticalPosition: "top"
          });
        },
        error => {
          this._snackBar.open("Failed", null, {
            duration: 2000,
            horizontalPosition: "center",
            panelClass: ["background-red"],
            verticalPosition: "top"
          });
        }
      );
    this.slot = '';
    this.route = '';
  }

  close() {
    this.dialogRef.close();
  }
}
