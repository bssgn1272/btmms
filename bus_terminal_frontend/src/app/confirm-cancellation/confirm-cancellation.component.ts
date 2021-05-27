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

    this.httpClient
      .put("/main/api/approve/reservations/requests/" + this.data.row.res_uuid, {
          reservation_status: this.status,
          status: this.status,
      })
      .subscribe(
        () => {
          this._location.back();
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
