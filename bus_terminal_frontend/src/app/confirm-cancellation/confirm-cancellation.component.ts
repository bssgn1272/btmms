import { Component, OnInit, Optional, Inject } from "@angular/core";
import { MatDialogRef, MAT_DIALOG_DATA, MatSnackBar } from "@angular/material";
import { HttpClient } from "@angular/common/http";
import { ActivatedRoute, Router } from "@angular/router";
import { Location, DatePipe } from "@angular/common";

@Component({
  selector: "app-confirm-cancellation",
  templateUrl: "./confirm-cancellation.component.html",
  styleUrls: ["./confirm-cancellation.component.scss"],
})
export class ConfirmCancellationComponent implements OnInit {
  slot = "";
  slot_one = "open";
  slot_two = "open";
  slot_three = "open";
  slot_four = "open";
  slot_five = "open";
  slot_six = "open";
  slot_seven = "open";
  slot_eight = "open";
  slot_nine = "open";
  time = "";
  id: any;
  status: string;
  slot_status: any;
  constructor(
    public dialogRef: MatDialogRef<ConfirmCancellationComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: any,
    private httpClient: HttpClient,
    private route: ActivatedRoute,
    private router: Router,
    private _snackBar: MatSnackBar,
    public _location: Location
  ) {}

  ngOnInit() {}

  confirmCancellation() {
    this.slot = this.data.row.slot;
    this.slot_status = this.data.row.username;
    this.time = this.data.row.time;
    this.id = this.data.row.id;
    this.status = "C";

    if (this.slot === "slot_one") {
      this.httpClient
        .put("/main/api/slots/close", {
          time: this.time,
          slot_one: this.slot_one,
        })
        .toPromise();
    } else if (this.slot === "slot_two") {
      this.httpClient
        .put("/main/api/slots/close", {
          time: this.time,
          slot_two: this.slot_two,
        })
        .toPromise();
    } else if (this.slot === "slot_three") {
      this.httpClient
        .put("/main/api/slots/close", {
          time: this.time,
          slot_three: this.slot_three,
        })
        .toPromise();
    } else if (this.slot === "slot_four") {
      this.httpClient
        .put("/main/api/slots/close", {
          time: this.time,
          slot_four: this.slot_four,
        })
        .toPromise();
    } else if (this.slot === "slot_five") {
      this.httpClient
        .put("/main/api/slots/close", {
          time: this.time,
          slot_five: this.slot_five,
        })
        .toPromise();
    } else if (this.slot === "slot_six") {
      this.httpClient
        .put("/main/api/slots/close", {
          time: this.time,
          slot_six: this.slot_six,
        })
        .toPromise();
    } else if (this.slot === "slot_seven") {
      this.httpClient
        .put("/main/api/slots/close", {
          time: this.time,
          slot_seven: this.slot_seven,
        })
        .toPromise();
    } else if (this.slot === "slot_eight") {
      this.httpClient
        .put("/main/api/slots/close", {
          time: this.time,
          slot_eight: this.slot_eight,
        })
        .toPromise();
    } else if (this.slot === "slot_nine") {
      this.httpClient
        .put("/main/api/slots/close", {
          time: this.time,
          slot_nine: this.slot_nine,
        })
        .toPromise();
    }
    this.httpClient
      .put("/main/api/approve/reservations/requests/" + this.data.row.res_uuid, {
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
          this._snackBar.open("Successfully Updated", null, {
            duration: 1000,
            horizontalPosition: "center",
            panelClass: ["blue-snackbar"],
            verticalPosition: "top",
          });
        },
        () => {
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
