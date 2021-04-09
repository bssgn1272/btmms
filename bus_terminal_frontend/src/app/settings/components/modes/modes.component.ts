import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
import { MatDialogRef, MatSnackBar } from "@angular/material";
import { DestinationDayComponent } from "app/destination-day/destination-day.component";
import { HttpClient } from "@angular/common/http";
import { ActivatedRoute, Router } from "@angular/router";
import { DestinationDayService } from "app/destination-day/destination-day.service";
import { Location, DatePipe } from "@angular/common";

@Component({
  selector: "app-modes",
  templateUrl: "./modes.component.html",
  styleUrls: ["./modes.component.scss"],
})
export class ModesComponent implements OnInit {
  modesForm: FormGroup;
  url = "/main/api/workflow";
  submitted: boolean;
  constructor(
    private _formBuilder: FormBuilder,
    public dialogRef: MatDialogRef<ModesComponent>,
    private httpClient: HttpClient,
    private routes: ActivatedRoute,
    private router: Router,
    private destinationTime: DestinationDayService,
    private _snackBar: MatSnackBar,
    private _location: Location
  ) {
    this.modesForm = this._formBuilder.group({
      mode: ["", Validators.required],
      description: ["", Validators.required],
    });
  }

  // convenience getter for easy access to form fields
  get f() {
    return this.modesForm.controls;
  }

  ngOnInit() {}

  onSubmit() {
    this.submitted = true;
    this.httpClient
      .post(this.url, {
        mode: this.f.mode.value,
        description: this.f.description.value,
      })
      .subscribe(
        (data) => {
          this._snackBar.open("Successfully Created", null, {
            duration: 1000,
            horizontalPosition: "center",
            panelClass: ["blue-snackbar"],
            verticalPosition: "top",
          });
          this.modesForm.reset();
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
