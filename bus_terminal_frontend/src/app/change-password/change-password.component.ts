import { Component, OnInit, Optional, Inject } from "@angular/core";
import { MatDialogRef, MAT_DIALOG_DATA, MatSnackBar } from "@angular/material";
import { HttpClient } from "@angular/common/http";
import { ActivatedRoute, Router } from "@angular/router";
import { Location } from "@angular/common";
import { FormGroup, Validators, FormBuilder, ValidatorFn, ValidationErrors } from "@angular/forms";
import { SettingsService } from "app/settings/settings.service";

@Component({
  selector: "app-change-password",
  templateUrl: "./change-password.component.html",
  styleUrls: ["./change-password.component.scss"],
})
export class ChangePasswordComponent implements OnInit {
  passwordForm: FormGroup;
  submitted = false;
  activated = false;
  minPw = 8;
  currentUser: any;
  constructor(
    public dialogRef: MatDialogRef<ChangePasswordComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: any,
    private httpClient: HttpClient,
    private route: ActivatedRoute,
    private router: Router,
    private _snackBar: MatSnackBar,
    public _location: Location,
    private settings: SettingsService,
    private _formBuilder: FormBuilder
  ) {  }

  get password() { return this.passwordForm.get('password'); }
  get password2() { return this.passwordForm.get('password2'); }

  onPasswordInput() {
    if (this.passwordForm.hasError('passwordMismatch'))
      this.password2.setErrors([{'passwordMismatch': true}]);
    else
      this.password2.setErrors(null);
  }

   /* Handle form errors in Angular 8 */
   public errorHandling = (control: string, error: string) => {
    return this.passwordForm.controls[control].hasError(error);
  };

  public getFromLocalStorage() {
    const users = JSON.parse(localStorage.getItem("currentUser"));
    return users;
  }

  ngOnInit() {
    this.currentUser = this.getFromLocalStorage();
    this.activated = this.data.activated;
    this.passwordForm = this._formBuilder.group({
      password: ["", Validators.required, Validators.minLength(this.minPw)],
      password2: ["", Validators.required],
    }, {validator: passwordMatchValidator});
  }

  get f() {
    return this.passwordForm.controls;
  }

  async changePassword(){
    this.submitted = true;
    if (this.passwordForm.invalid) {
      console.log("invalid input");
      return;
    }
    await this.settings.changePassword(this.currentUser.username, this.passwordForm.get('password').value).then((res) => {
      this.currentUser.account_status = res.data.response.AUTHENTICATION.data.account_status;
      localStorage.setItem("currentUser", JSON.stringify(this.currentUser));
      console.log(res.data.response.AUTHENTICATION.data.account_status);
      this.dialogRef.close();
      this._snackBar.open("Password Successfully Changed", null, {
        duration: 5000,
        horizontalPosition: "center",
        panelClass: ["blue-snackbar"],
        verticalPosition: "top",
      });
    });
  }

  close() {
    this.dialogRef.close();
  }
}

export const passwordMatchValidator: ValidatorFn = (formGroup: FormGroup): ValidationErrors | null => {
  if (formGroup.get('password').value === formGroup.get('password2').value)
    return null;
  else
    return {passwordMismatch: true};
};
