import { Component, OnInit, Optional, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA, MatSnackBar } from '@angular/material';
import { HttpClient, HttpParams } from '@angular/common/http';
import { ActivatedRoute, Router } from '@angular/router';
import { OpenSlotsService } from '../roservation-requests/slot.service';
import {Location} from '@angular/common';

@Component({
  selector: 'app-approve-reservation',
  templateUrl: './approve-reservation.component.html',
  styleUrls: ['./approve-reservation.component.scss'],
})
export class ApproveReservationComponent implements OnInit {
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
  user = '';
  time = '';
  slot_status: any;
  id: any;
  status: string;
  constructor(
      public dialogRef: MatDialogRef<ApproveReservationComponent>,
      @Optional() @Inject(MAT_DIALOG_DATA) public data: any,
      private httpClient: HttpClient,
      private route: ActivatedRoute,
      private router: Router,
      private _snackBar: MatSnackBar,
      public _location: Location
  ) {}

  ngOnInit() {}

  approve() {
    this.slot = this.data.row.slot;
    this.slot_status = this.data.row.username;
    this.time = this.data.row.time;
    this.id = this.data.row.user_id;
    this.user = this.data.row.username;
    this.status = 'A';

    this.httpClient
      .put('/main/api/approve/reservations/requests/' + this.data.row.res_uuid, {
          reservation_status: this.status,
          status: this.status,
      })
      .subscribe(
          () => {
            this.httpClient
              .get('main/api/users/' + this.data.row.user_id)
              .subscribe(
                (received) => {
                  console.log('User Data>>> ',  received['data']);
                  let message = 'Dear operator,';
                  message += '\nYour departure slot has been approved.'
                  message += '\nTime: ' + this.data.row.reserved_time.split('T')[0] + ' ' + this.data.row.time;
                  message += '\nDestination: ' + this.data.row.end_route;
                  message += '\nSlot: ' + this.data.row.slot;
                  message += '\nBus Registration: ' + this.data.row.license_plate;
                  message += '\nThank you.'

                  let body = new HttpParams();
                  body = body.set("receiver", received['data'][0].mobile);
                  body = body.set("msg", message);
                  this.httpClient.get("/main/api/sms", { params: body }).subscribe(
                    (data) => {},
                    (error) => {}
                  );

                  const subject = 'Approval of' + ' ' + this.data.row.slot + ' reservation. ' + ' Destination: ' + this.data.row.end_route +
                      ' Bus Registration: ' + this.data.row.license_plate;
                  let bodyc = new HttpParams();
                  bodyc = bodyc.set("email", received['data'][0].email);
                  bodyc = bodyc.set("user", received['data'][0].username);
                  bodyc = bodyc.set("subject", subject);
                  bodyc = bodyc.set("msg", message);
                  this.httpClient.get("/main/api/email", { params: bodyc }).subscribe(
                    (data) => {},
                    (error) => {}
                  );
                },
                (error) => {}
              )
          
            this._location.back();
            this._snackBar.open('Successfully Updated', null, {
              duration: 1000,
              horizontalPosition: 'center',
              panelClass: ['blue-snackbar'],
              verticalPosition: 'top',
            });
          },
          () => {
            this._snackBar.open('Failed', null, {
              duration: 2000,
              horizontalPosition: 'center',
              panelClass: ['background-red'],
              verticalPosition: 'top',
            });
          }
      );
  }

  close() {
    this.dialogRef.close();
  }
}
