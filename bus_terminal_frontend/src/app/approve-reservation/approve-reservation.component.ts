import { Component, OnInit, Optional, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA, MatSnackBar } from '@angular/material';
import { HttpClient } from '@angular/common/http';
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

    if (this.slot === 'slot_one') {
      this.httpClient
          .put('/main/api/slots/close', {
            time: this.time,
            slot_one: this.user,
          })
          .toPromise();
    } else if (this.slot === 'slot_two') {
      this.httpClient
          .put('/main/api/slots/close', {
            time: this.time,
            slot_two: this.user,
          })
          .toPromise();
    } else if (this.slot === 'slot_three') {
      this.httpClient
          .put('/main/api/slots/close', {
            time: this.time,
            slot_three: this.user,
          })
          .toPromise();
    } else if (this.slot === 'slot_four') {
      this.httpClient
          .put('/main/api/slots/close', {
            time: this.time,
            slot_four: this.user,
          })
          .toPromise();
    } else if (this.slot === 'slot_five') {
      this.httpClient
          .put('/main/api/slots/close', {
            time: this.time,
            slot_five: this.user,
          })
          .toPromise();
    } else if (this.slot === 'slot_six') {
      this.httpClient
          .put('/main/api/slots/close', {
            time: this.time,
            slot_six: this.user,
          })
          .toPromise();
    } else if (this.slot === 'slot_seven') {
      this.httpClient
          .put('/main/api/slots/close', {
            time: this.time,
            slot_seven: this.user,
          })
          .toPromise();
    } else if (this.slot === 'slot_eight') {
      this.httpClient
          .put('/main/api/slots/close', {
            time: this.time,
            slot_eight: this.user,
          })
          .toPromise();
    } else if (this.slot === 'slot_nine') {
      this.httpClient
          .put('/main/api/slots/close', {
            time: this.time,
            slot_nine: this.user,
          })
          .toPromise();
    }
    this.httpClient
        .put('/main/api/approve/reservations/requests/' + this.data.row.res_uuid, {
            reservation_status: this.status,
        })
        .subscribe(
            () => {
              this._location.back();
              // this.router
              //   .navigateByUrl('/veiw-resavations-requests', {
              //     skipLocationChange: true
              //   })
              //   .then(() => {
              //     this.router.navigate([decodeURI(this._location.path())]);
              //   });
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
