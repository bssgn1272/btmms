import { Component, OnInit, Optional, Inject } from "@angular/core";
import { MatDialogRef, MAT_DIALOG_DATA, MatSnackBar } from "@angular/material";
import { RejectComponent } from "app/reject/reject.component";
import { HttpClient, HttpParams } from "@angular/common/http";
import { ActivatedRoute, Router } from "@angular/router";
import { Location, DatePipe } from "@angular/common";
import { SettingsService } from "app/settings/settings.service";
import * as moment from "moment";

@Component({
  selector: "app-cancel-reservation",
  templateUrl: "./cancel-reservation.component.html",
  styleUrls: ["./cancel-reservation.component.scss"],
})
export class CancelReservationComponent implements OnInit {
  slot = "";
  slot_one = "open";
  slot_two = "open";
  slot_three = "open";
  slot_four = "open";
  slot_five = "open";
  time = "";
  id: any;
  status: string;
  mode: any;
  workFlow: any;
  penalty: any;
  penaltyA: any;
  dueTime: any;
  currentTime: any;
  testTime: any;
  bus_id: any;
  userItems: any;
  constructor(
    public dialogRef: MatDialogRef<RejectComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: any,
    private httpClient: HttpClient,
    private route: ActivatedRoute,
    private router: Router,
    private _snackBar: MatSnackBar,
    public _location: Location,
    private settings: SettingsService
  ) {}

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem("currentUser"));
    return users;
  }

  ngOnInit() {
    this.userItems = this.getFromLocalStrorage();
    this.settings.getModes().then((res) => {
      this.mode = res.data;
      this.workFlow = this.mode.filter((x) => x.status === "Active")[0];
      console.log(this.workFlow);
    });

    this.settings.getDUeTimes().then((res) => {
      this.penalty = res.data;
      this.penaltyA = this.penalty.filter((x) => x.status === "Active")[0];
    });
  }

  cancel() {
    this.slot = this.data.row.slot;
    this.id = this.data.row.ID;

    console.log("CHECK FOR ID>>>>", this.data.row);

    this.bus_id = this.data.row.bus_id;
    this.status = "C";
    this.time = this.data.row.time;
    this.currentTime = new Date();

    this.dueTime = moment(this.penaltyA.due_time, "hh:mm:ss");

    this.testTime = moment(this.dueTime._d, "MMMM Do YYYY, hh:mm:ss");
    if (this.currentTime > this.dueTime._d) {
      console.log("USER ID>>>>", this.data.row.user_id);
      console.log("BUS ID>>>>", this.data.row.bus_id);

      this.httpClient
        .post("/api/penalty", {
          bus_operator_id: this.data.row.user_id,
          bus_id: this.data.row.bus_id,
          date_booked: this.currentTime,
          status: "Unpaid",
          type: "Late Cancellation",
        })
        .subscribe((x) => {
          const message = "Late Cancellation Penalty";

          let body = new HttpParams();
          body = body.set("receiver", this.userItems.mobile);
          body = body.set("msg", message);
          this.httpClient.get("/api/sms", { params: body }).subscribe(
            (data) => {},
            (error) => {}
          );

          const subject = "Late Cancellation Penalty";
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

    if (this.slot === "slot_one") {
      this.httpClient
        .put("/api/slots/close", {
          time: this.time,
          slot_one: this.slot_one,
        })
        .toPromise();
    } else if (this.slot === "slot_two") {
      this.httpClient
        .put("/api/slots/close", {
          time: this.time,
          slot_two: this.slot_two,
        })
        .toPromise();
    } else if (this.slot === "slot_three") {
      this.httpClient
        .put("/api/slots/close", {
          time: this.time,
          slot_three: this.slot_three,
        })
        .toPromise();
    } else if (this.slot === "slot_four") {
      this.httpClient
        .put("/api/slots/close", {
          time: this.time,
          slot_four: this.slot_four,
        })
        .toPromise();
    } else if (this.slot === "slot_five") {
      this.httpClient
        .put("/api/slots/close", {
          time: this.time,
          slot_five: this.slot_five,
        })
        .toPromise();
    }
    console.log(this.id);
    this.httpClient
      .put("/api/approve/reservations/requests/" + this.data.row.res_uuid, {
        status: this.status,
      })
      .subscribe(
        (data) => {
          const message = "Reservation Cancelled Successfully";

          let body = new HttpParams();
          body = body.set("receiver", this.userItems.mobile);
          body = body.set("msg", message);
          this.httpClient.get("/api/sms", { params: body }).subscribe(
            (data) => {},
            (error) => {}
          );

          const subject = "Reservation Cancellation";
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

  requestCancellation() {
    this.dueTime = moment(this.penaltyA.due_time, "hh:mm:ss");

    this.testTime = moment(this.dueTime._d, "MMMM Do YYYY, hh:mm:ss");
    if (this.currentTime > this.dueTime) {
      console.log("USER ID>>>>", this.data.row.user_id);
      console.log("BUS ID>>>>", this.data.row.bus_id);

      this.httpClient
        .post("/api/penalty", {
          bus_operator_id: this.data.row.user_id,
          bus_id: this.data.row.bus_id,
          date_booked: this.currentTime,
          status: "Unpaid",
          type: "Late Cancellation",
        })
        .subscribe((x) => {
          const message = "Late Cancellation Penalty";

          let body = new HttpParams();
          body = body.set("receiver", this.userItems.mobile);
          body = body.set("msg", message);
          this.httpClient.get("/api/sms", { params: body }).subscribe(
            (data) => {},
            (error) => {}
          );

          const subject = "Late Cancellation Penalty";
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
      .put("/api/approve/reservations/requests/" + this.data.row.res_uuid, {
        status: this.status,
      })
      .subscribe(
        (data) => {
          const message = "Reservation Cancelled Successfully";

          let body = new HttpParams();
          body = body.set("receiver", this.userItems.mobile);
          body = body.set("msg", message);
          this.httpClient.get("/api/sms", { params: body }).subscribe(
            (data) => {},
            (error) => {}
          );

          const subject = "Reservation Cancellation";
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
