import { Component, OnInit, Optional, Inject } from "@angular/core";
import { MatDialogRef, MAT_DIALOG_DATA, MatSnackBar } from "@angular/material";
import { RejectComponent } from "app/reject/reject.component";
import { HttpClient, HttpParams } from "@angular/common/http";
import { ActivatedRoute, Router } from "@angular/router";
import { Location, DatePipe } from "@angular/common";
import { SettingsService } from "app/settings/settings.service";
import { FormGroup, Validators, FormBuilder } from "@angular/forms";
import { OptionsService } from "../options/options.service";

@Component({
  selector: "app-change-option",
  templateUrl: "./change-option.component.html",
  styleUrls: ["./change-option.component.scss"],
})
export class ChangeOptionComponent implements OnInit {
  _id: any;
  id: any;
  userItems: any;
  value: any;
  optionForm: FormGroup;
  submitted = false;
  constructor(
    public dialogRef: MatDialogRef<RejectComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: any,
    private httpClient: HttpClient,
    private route: ActivatedRoute,
    private router: Router,
    private _snackBar: MatSnackBar,
    public _location: Location,
    private _formBuilder: FormBuilder,
    private OptionsService: OptionsService,
    private settings: SettingsService
  ) {
    this.optionForm = this._formBuilder.group({
      optionValue: ["", Validators.required],
    });
  }

  /* Handle form errors in Angular 8 */
  public errorHandling = (control: string, error: string) => {
    return this.optionForm.controls[control].hasError(error);
  };

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem("currentUser"));
    return users;
  }

  async ngOnInit() {
    console.log("DATA PASSED", this.data.row);
    this._id = this.data.row.id;
    this.userItems = this.getFromLocalStrorage();
    this.value = this.data.row.option_value;
  }

  get f() {
    return this.optionForm.controls;
  }

  update() {
    this.submitted = true;

    if (this.optionForm.invalid) {
      return;
    }

    this.id = this.data.row.ID;
    this.httpClient
      .put("/main/api/options/" + this.data.row.ID, {
        option_value: this.f.optionValue.value,
        option_name: this.data.row.option_name
      })
      .subscribe(
        (data) => {
          this._location.back();
          this._snackBar.open("Option Successfully Updated", null, {
            duration: 3000,
            horizontalPosition: "center",
            panelClass: ["blue-snackbar"],
            verticalPosition: "top",
          });
          this.dialogRef.close();
        },
        (error) => {
          this._snackBar.open("Update Failed", null, {
            duration: 3000,
            horizontalPosition: "center",
            panelClass: ["background-red"],
            verticalPosition: "top",
          });
          this.dialogRef.close();
        }
      );
  }

  close() {
    this.dialogRef.close();
  }
}
