import { Component, OnInit, Optional, Inject } from "@angular/core";
import { MatDialogRef, MAT_DIALOG_DATA, MatSnackBar } from "@angular/material";
import { HttpClient } from "@angular/common/http";
import { ActivatedRoute, Router } from "@angular/router";
import { OpenSlotsService } from "../roservation-requests/slot.service";

@Component({
  selector: "app-approve-reservation",
  templateUrl: "./approve-reservation.component.html",
  styleUrls: ["./approve-reservation.component.scss"],
})
export class ApproveReservationComponent implements OnInit {
  status = "";
  id = 0;
  slot = "";
  slot_one = "open";
  slot_two = "open";
  slot_three = "open";
  slot_four = "open";
  slot_five = "open";
  user = "";
  time = "";
  slot_status: any;
  constructor(
    public dialogRef: MatDialogRef<ApproveReservationComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: any,
    private httpClient: HttpClient,
    private route: ActivatedRoute,
    private router: Router,
    private _snackBar: MatSnackBar,
    public _location: Location,
    private slots: OpenSlotsService
  ) {}

  ngOnInit() {}

  // approve(row) {
  //   this.slot = row.slot;
  //   this.id = row.ID;
  //   this.status = 'A';
  //   this.time = row.time;
  //   this.user = row.username;
  //   console.log(this.slot);

  //   if (this.slot === 'slot_one') {
  //     this.httpClient
  //       .put('/api/slots/close', {
  //         time: this.time,
  //         slot_one: this.user
  //       })
  //       .toPromise();
  //   }
  //   if (this.slot === 'slot_two') {
  //     this.httpClient
  //       .put('/api/slots/close', {
  //         time: this.time,
  //         slot_two: this.user
  //       })
  //       .toPromise();
  //   }
  //   if (this.slot === 'slot_three') {
  //     this.httpClient
  //       .put('/api/slots/close', {
  //         time: this.time,
  //         slot_three: this.user
  //       })
  //       .toPromise();
  //   }
  //   if (this.slot === 'slot_four') {
  //     this.httpClient
  //       .put('/api/slots/close', {
  //         time: this.time,
  //         slot_four: this.user
  //       })
  //       .toPromise();
  //   }
  //   if (this.slot === 'slot_five') {
  //     this.httpClient
  //       .put('/api/slots/close', {
  //         time: this.time,
  //         slot_five: this.user
  //       })
  //       .toPromise();
  //   }
  //   this.httpClient
  //     .put('/api/approve/reservations/requests/' + this.data.row.res_uuid, {
  //       status: this.status
  //     })
  //     .subscribe(
  //       () => {
  //         window.location.reload();
  //         this._snackBar.open('Successfully Updated', null, {
  //           duration: 1000,
  //           horizontalPosition: 'center',
  //           panelClass: ['blue-snackbar'],
  //           verticalPosition: 'top'
  //         });
  //       },
  //       () => {
  //         this._snackBar.open('Failed', null, {
  //           duration: 2000,
  //           horizontalPosition: 'center',
  //           panelClass: ['background-red'],
  //           verticalPosition: 'top'
  //         });
  //       }
  //     );
  //   console.log(row);
  // }

  reject() {
    // this.slot = row.slot;
    // this.slot_status = row.username;
    // this.time = row.time;
    // console.log(this.slot);
    // this.id = row.id;
    // this.status = 'R';
    // if (this.slot === 'slot_one') {
    //   this.httpClient
    //     .put('/api/slots/close', {
    //       time: this.time,
    //       slot_one: this.slot_one
    //     })
    //     .toPromise();
    // } else if (this.slot === 'slot_two') {
    //   this.httpClient
    //     .put('/api/slots/close', {
    //       time: this.time,
    //       slot_two: this.slot_two
    //     })
    //     .toPromise();
    // } else if (this.slot === 'slot_three') {
    //   this.httpClient
    //     .put('/api/slots/close', {
    //       time: this.time,
    //       slot_three: this.slot_three
    //     })
    //     .toPromise();
    // } else if (this.slot === 'slot_four') {
    //   this.httpClient
    //     .put('/api/slots/close', {
    //       time: this.time,
    //       slot_four: this.slot_four
    //     })
    //     .toPromise();
    // } else if (this.slot === 'slot_five') {
    //   this.httpClient
    //     .put('/api/slots/close', {
    //       time: this.time,
    //       slot_five: this.slot_five
    //     })
    //     .toPromise();
    // }
    // this.httpClient
    //   .put('/api/approve/reservations/requests/' + this.data.row.res_uuid, {
    //     status: this.status
    //   })
    //   .subscribe(
    //     () => {
    //       this._location.back();
    //       // this.router
    //       //   .navigateByUrl('/veiw-resavations-requests', {
    //       //     skipLocationChange: true
    //       //   })
    //       //   .then(() => {
    //       //     this.router.navigate([decodeURI(this._location.path())]);
    //       //   });
    //       this._snackBar.open('Successfully Updated', null, {
    //         duration: 1000,
    //         horizontalPosition: 'center',
    //         panelClass: ['blue-snackbar'],
    //         verticalPosition: 'top'
    //       });
    //     },
    //     () => {
    //       this._snackBar.open('Failed', null, {
    //         duration: 2000,
    //         horizontalPosition: 'center',
    //         panelClass: ['background-red'],
    //         verticalPosition: 'top'
    //       });
    //     }
    //   );
    // console.log(this.data.row);
  }
}
