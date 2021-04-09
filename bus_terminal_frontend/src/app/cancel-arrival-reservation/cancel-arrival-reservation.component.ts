import {Component, Inject, OnInit, Optional} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {RejectComponent} from '../reject/reject.component';
import {HttpClient, HttpParams} from '@angular/common/http';
import {ActivatedRoute, Router} from '@angular/router';
import {Location} from '@angular/common';
import {SettingsService} from '../settings/settings.service';
import { MatSnackBar } from '@angular/material';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';

@Component({
  selector: 'app-cancel-arrival-reservation',
  templateUrl: './cancel-arrival-reservation.component.html',
  styleUrls: ['./cancel-arrival-reservation.component.scss']
})
export class CancelArrivalReservationComponent implements OnInit {
  slot = '';
  slot_one = 'open';
  slot_two = 'open';
  slot_three = 'open';
  slot_four = 'open';
  slot_five = 'open';
  slot_six = 'open';
  slot_seven = 'open';
  slot_eight = 'open';
  slot_nine = 'open';
  time = '';
  id: any;
  status: string;
  mode: any;
  workFlow: any;
  penalty: any;
  penaltyA: any;
  charge: any;
  late: boolean;
  dueTime: any;
  currentTime: any;
  testTime: any;
  bus_id: any;
  userItems: any;
  cancellationReason: any;
  reasonForm: FormGroup;
  submitted = false;

  constructor(
      public dialogRef: MatDialogRef<CancelArrivalReservationComponent>,
      @Optional() @Inject(MAT_DIALOG_DATA) public data: any,
      private httpClient: HttpClient,
      private route: ActivatedRoute,
      private router: Router,
      private _snackBar: MatSnackBar,
      public _location: Location,
      private _formBuilder: FormBuilder,
      private settings: SettingsService
  ) {
    this.reasonForm = this._formBuilder.group({
      reason: ['', Validators.required],
    });
  }

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem('currentUser'));
    return users;
  }

  async ngOnInit() {
    this.late = false;
    this.userItems = this.getFromLocalStrorage();
    await this.settings.getModes().then((res) => {
      this.mode = res.data;
      this.workFlow = this.mode.filter((x) => x.status === 'Active')[0];
      console.log(this.workFlow);
    });

    await this.settings.getOptions().then((res) => {
      this.penalty = res.data;
      console.log(res.data);
      this.penaltyA = this.penalty.filter((x) => x.option_name === 'minutes_before_cancellation')[0];

      const dt = this.data.row.reserved_time.split('T')[0].split('-');
      const tm = this.data.row.time.split(':');
      this.currentTime = new Date();
      this.dueTime = new Date(dt[0], dt[1] - 1, dt[2], tm[0], tm[1], 0, 0);
      this.dueTime.setMinutes(this.dueTime.getMinutes() - this.penaltyA.option_value);
      if (this.currentTime > this.dueTime) {
        this.late = true;
        this.settings.getLateCancellationCharge(this.data.row.bus_id).then((resA) => {
          this.charge = resA.data[0];
        });
      }
    });
  }

  get f() {
    return this.reasonForm.controls;
  }

  cancel() {
    this.submitted = true;

    if (this.reasonForm.invalid) {
      return;
    }

    this.slot = this.data.row.slot;
    this.id = this.data.row.ID;

    console.log('CHECK FOR ID>>>>', this.data.row);

    this.bus_id = this.data.row.bus_id;
    this.status = 'C';
    this.time = this.data.row.time;
    if (this.late) {
      console.log('USER ID>>>>', this.data.row.user_id);
      console.log('BUS ID>>>>', this.data.row.bus_id);

      this.httpClient
          .post('/main/api/penalty', {
            bus_operator_id: this.data.row.user_id,
            bus_id: this.data.row.bus_id,
            date_booked: this.currentTime,
            penalty_status: 'Unpaid',
            type: 'Late Cancellation',
          })
          .subscribe((x) => {
            const message = 'Late Cancellation of' + ' ' + this.data.row.slot + 'reservation. ' + 'Destination: ' + this.data.row.end_route +
                ' Bus Registration: ' + this.data.row.license_plate;

            let body = new HttpParams();
            body = body.set('receiver', this.userItems.mobile);
            body = body.set('msg', message);
            this.httpClient.get('/api/sms', { params: body }).subscribe(
                (data) => {},
                (error) => {}
            );

            const subject = 'Late Cancellation of' + ' ' + this.data.row.slot + 'reservation. ' + 'Destination: ' + this.data.row.end_route +
            ' Bus Registration: ' + this.data.row.license_plate;
            let bodyc = new HttpParams();
            bodyc = bodyc.set('email', this.userItems.email);
            bodyc = bodyc.set('user', this.userItems.username);
            bodyc = bodyc.set('subject', subject);
            bodyc = bodyc.set('msg', message);
            this.httpClient.get('/api/email', { params: bodyc }).subscribe(
                (data) => {},
                (error) => {}
            );
          });
    }

    if (this.slot === 'slot_one') {
      this.httpClient
          .put('/api/slots/close', {
            time: this.time,
            slot_one: this.slot_one,
          })
          .toPromise();
    } else if (this.slot === 'slot_two') {
      this.httpClient
          .put('/api/slots/close', {
            time: this.time,
            slot_two: this.slot_two,
          })
          .toPromise();
    } else if (this.slot === 'slot_three') {
      this.httpClient
          .put('/api/slots/close', {
            time: this.time,
            slot_three: this.slot_three,
          })
          .toPromise();
    } else if (this.slot === 'slot_four') {
      this.httpClient
          .put('/api/slots/close', {
            time: this.time,
            slot_four: this.slot_four,
          })
          .toPromise();
    } else if (this.slot === 'slot_five') {
      this.httpClient
          .put('/api/slots/close', {
            time: this.time,
            slot_five: this.slot_five,
          })
          .toPromise();
    } else if (this.slot === 'slot_six') {
      this.httpClient
          .put('/api/slots/close', {
            time: this.time,
            slot_six: this.slot_six,
          })
          .toPromise();
    } else if (this.slot === 'slot_seven') {
      this.httpClient
          .put('/api/slots/close', {
            time: this.time,
            slot_seven: this.slot_seven,
          })
          .toPromise();
    } else if (this.slot === 'slot_eight') {
      this.httpClient
          .put('/api/slots/close', {
            time: this.time,
            slot_eight: this.slot_eight,
          })
          .toPromise();
    } else if (this.slot === 'slot_nine') {
      this.httpClient
          .put('/api/slots/close', {
            time: this.time,
            slot_nine: this.slot_nine,
          })
          .toPromise();
    }
    console.log(this.id);
    this.httpClient
        .put('/api/approve/arreservations/requests/' + this.data.row.res_uuid, {
            reservation_status: this.status,
          cancellation_reason: this.cancellationReason,
        })
        .subscribe(
            (data) => {
              const message = 'Cancellation of' + ' ' + this.data.row.slot + 'reservation. ' + 'Destination: ' + this.data.row.end_route +
                  ' Bus Registration: ' + this.data.row.license_plate;

              let body = new HttpParams();
              body = body.set('receiver', this.userItems.mobile);
              body = body.set('msg', message);
              this.httpClient.get('/api/sms', { params: body }).subscribe(
                  (data) => {},
                  (error) => {}
              );

              const subject = 'Cancellation of' + ' ' + this.data.row.slot + 'reservation. ' + 'Destination: ' + this.data.row.end_route +
                  ' Bus Registration: ' + this.data.row.license_plate;
              let bodyc = new HttpParams();
              bodyc = bodyc.set('email', this.userItems.email);
              bodyc = bodyc.set('user', this.userItems.username);
              bodyc = bodyc.set('subject', subject);
              bodyc = bodyc.set('msg', message);
              this.httpClient.get('/api/email', { params: bodyc }).subscribe(
                  (data) => {},
                  (error) => {}
              );
              this._location.back();
              this._snackBar.open('Successfully Updated', null, {
                duration: 1000,
                horizontalPosition: 'center',
                panelClass: ['blue-snackbar'],
                verticalPosition: 'top',
              });
            },
            (error) => {
              this._snackBar.open('Failed', null, {
                duration: 2000,
                horizontalPosition: 'center',
                panelClass: ['background-red'],
                verticalPosition: 'top',
              });
            }
        );
  }


  requestCancellation() {
    this.submitted = true;
    if (this.reasonForm.invalid) {
      return;
    }

    if (this.late) {
      console.log("USER ID>>>>", this.data.row.user_id);
      console.log("BUS ID>>>>", this.data.row.bus_id);

      this.httpClient
          .post("/main/api/penalty", {
            bus_operator_id: this.data.row.user_id,
            bus_id: this.data.row.bus_id,
            date_booked: this.currentTime,
            penalty_status: "Unpaid",
            type: "Late Cancellation",
          })
          .subscribe((x) => {
            const message = 'Late Cancellation request of' + ' ' + this.data.row.slot + 'reservation. ' + 'Destination: ' + this.data.row.end_route +
                ' Bus Registration: ' + this.data.row.license_plate;

            let body = new HttpParams();
            body = body.set("receiver", this.userItems.mobile);
            body = body.set("msg", message);
            this.httpClient.get("/api/sms", { params: body }).subscribe(
                (data) => {},
                (error) => {}
            );

            const subject = 'Late Cancellation request of' + ' ' + this.data.row.slot + 'reservation. ' + 'Destination: ' + this.data.row.end_route +
                ' Bus Registration: ' + this.data.row.license_plate;
            let bodyc = new HttpParams();
            bodyc = bodyc.set("email", this.userItems.email);
            bodyc = bodyc.set("user", this.userItems.username);
            bodyc = bodyc.set("subject", subject);
            bodyc = bodyc.set("msg", message);
            this.httpClient.get("/api/email", { params: bodyc }).subscribe(
                (data) => {},
                (error) => {}
            );
          });
    }
    this.slot = this.data.row.slot;
    this.id = this.data.row.user_id;
    this.status = "PC";
    this.time = this.data.row.time;
    console.log(this.id);
    this.httpClient
        .put("/api/approve/arreservations/requests/" + this.data.row.res_uuid, {
            reservation_status: this.status,
          cancellation_reason: this.cancellationReason,
        })
        .subscribe(
            (data) => {
              const message = 'Cancellation request of' + ' ' + this.data.row.slot + 'reservation. ' + 'Destination: ' + this.data.row.end_route +
              ' Bus Registration: ' + this.data.row.license_plate;

              let body = new HttpParams();
              body = body.set("receiver", this.userItems.mobile);
              body = body.set("msg", message);
              this.httpClient.get("/api/sms", { params: body }).subscribe(
                  (data) => {},
                  (error) => {}
              );

              const subject = 'Cancellation request of' + ' ' + this.data.row.slot + 'reservation. ' + 'Destination: ' + this.data.row.end_route +
                  ' Bus Registration: ' + this.data.row.license_plate;
              let bodyc = new HttpParams();
              bodyc = bodyc.set("email", this.userItems.email);
              bodyc = bodyc.set("user", this.userItems.username);
              bodyc = bodyc.set("subject", subject);
              bodyc = bodyc.set("msg", message);
              this.httpClient.get("/api/email", { params: bodyc }).subscribe(
                  (data) => {},
                  (error) => {}
              );
              this._location.back();
              this._snackBar.open("Successfully Updated", null, {
                duration: 1000,
                horizontalPosition: "center",
                panelClass: ["blue-snackbar"],
                verticalPosition: "top",
              });
            },
            (error) => {
              this._snackBar.open("Failed", null, {
                duration: 2000,
                horizontalPosition: "center",
                panelClass: ["background-red"],
                verticalPosition: "top",
              });
            }
        );
  }

  close() {
    this.dialogRef.close();
  }

}


