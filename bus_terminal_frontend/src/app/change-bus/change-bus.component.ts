import { Component, OnInit, Optional, Inject } from "@angular/core";
import { MatDialogRef, MAT_DIALOG_DATA, MatSnackBar } from "@angular/material";
import { RejectComponent } from "app/reject/reject.component";
import { HttpClient, HttpParams } from "@angular/common/http";
import { ActivatedRoute, Router } from "@angular/router";
import { Location, DatePipe } from "@angular/common";
import { SettingsService } from "app/settings/settings.service";
import { FormGroup, Validators, FormBuilder } from "@angular/forms";
import { ChangeBusService } from "./change-bus.service";

@Component({
  selector: "app-change-bus",
  templateUrl: "./change-bus.component.html",
  styleUrls: ["./change-bus.component.scss"],
})
export class ChangeBusComponent implements OnInit {
  _id: any;
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
  buses: any[] = [];
  constructor(
    public dialogRef: MatDialogRef<RejectComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: any,
    private httpClient: HttpClient,
    private route: ActivatedRoute,
    private router: Router,
    private _snackBar: MatSnackBar,
    public _location: Location,
    private _formBuilder: FormBuilder,
    private changeBusService: ChangeBusService,
    private settings: SettingsService
  ) {
    this.reasonForm = this._formBuilder.group({
      bus: ["", Validators.required],
    });
  }

  /* Handle form errors in Angular 8 */
  public errorHandling = (control: string, error: string) => {
    return this.reasonForm.controls[control].hasError(error);
  };

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem("currentUser"));
    return users;
  }

  async ngOnInit() {
    console.log("DATA PASSED", this.data.row.res_uuid);
    this._id = this.data.row.res_uuid;
    this.loadBuses();
    this.late = false;
    this.userItems = this.getFromLocalStrorage();
    await this.settings.getModes().then((res) => {
      this.mode = res.data;
      this.workFlow = this.mode.filter((x) => x.status === "Active")[0];
      console.log(this.workFlow);
    });

    await this.settings.getOptions().then((res) => {
      this.penalty = res.data;
      console.log(res.data);
      this.penaltyA = this.penalty.filter((x) => x.option_name === "minutes_before_cancellation")[0];
      
      var dt = this.data.row.reserved_time.split("T")[0].split("-");
      var tm = this.data.row.time.split(":");
      this.currentTime = new Date();
      this.dueTime = new Date(dt[0], dt[1] - 1, dt[2], tm[0], tm[1], 0, 0);
      this.dueTime.setMinutes(this.dueTime.getMinutes() - this.penaltyA.option_value);
      if (this.currentTime > this.dueTime) {
        this.late = true;
        this.settings.getLateCancellationCharge(this.data.row.bus_id).then((res) => {
          this.charge = res.data[0];
        });
      }
    });
  }

  get f() {
    return this.reasonForm.controls;
  }

  update() {
    this.submitted = true;

    if (this.reasonForm.invalid) {
      return;
    }

    this.slot = this.data.row.slot;
    this.id = this.data.row.ID;

    console.log("CHECK FOR ID>>>>", this.data.row);

    this.bus_id = this.data.row.bus_id;
    this.status = "A";
    this.time = this.data.row.time;
    console.log(this.id);
    this.httpClient
      .put("/main/api/approve/reservations/requests/" + this.data.row.res_uuid, {
        bus_id: this.f.bus.value,
      })
      .subscribe(
        (data) => {
          const message = "Reservation Updated Successfully";

          let body = new HttpParams();
          body = body.set("receiver", this.userItems.mobile);
          body = body.set("msg", message);
          this.httpClient.get("/api/sms", { params: body }).subscribe(
            (data) => {},
            (error) => {}
          );

          const subject = "Reservation Updated";
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
    this.submitted = true;
    if (this.reasonForm.invalid) {
      return;
    }

    if (this.late) {
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
        cancellation_reason: this.cancellationReason,
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

  async loadBuses() {
    await this.changeBusService.getBusList(this._id).then((res) => {
      this.buses = res.data;
      console.log(this.buses);
    });
  }

  close() {
    this.dialogRef.close();
  }
}
