import { Component, OnInit } from "@angular/core";
import { FormGroup, FormBuilder, Validators } from "@angular/forms";
import { MatDialogRef, MatSnackBar } from "@angular/material";
import { ModesComponent } from "../modes/modes.component";
import { HttpClient } from "@angular/common/http";
import { ActivatedRoute, Router } from "@angular/router";
import { DestinationDayService } from "app/destination-day/destination-day.service";

import { Location, DatePipe } from "@angular/common";

@Component({
  selector: "app-due-time",
  templateUrl: "./due-time.component.html",
  styleUrls: ["./due-time.component.scss"],
})
export class DueTimeComponent implements OnInit {
  dueTimeForm: FormGroup;
  url = "/api/penalty/time";
  submitted: boolean;
  constructor(
    private _formBuilder: FormBuilder,
    public dialogRef: MatDialogRef<DueTimeComponent>,
    private httpClient: HttpClient,
    private _snackBar: MatSnackBar,
    private _location: Location
  ) {
    this.dueTimeForm = this._formBuilder.group({
      due_time: ["", Validators.required],
      description: ["", Validators.required],
    });
  }

  // convenience getter for easy access to form fields
  get f() {
    return this.dueTimeForm.controls;
  }

  ngOnInit() {}

  onSubmit() {
    this.submitted = true;
    this.httpClient
      .post(this.url, {
        due_time: this.f.due_time.value,
        description: this.f.description.value,
        status: "Inactive",
      })
      .subscribe(
        (data) => {
          this._snackBar.open("Successfully Created", null, {
            duration: 1000,
            horizontalPosition: "center",
            panelClass: ["blue-snackbar"],
            verticalPosition: "top",
          });
          this.dueTimeForm.reset();
          this._location.back();
          this.dialogRef.close();
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

  close() {}
}
