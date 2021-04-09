import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import {
  FormGroup,
  FormControl,
  FormBuilder,
  Validators
} from '@angular/forms';
import { AuthService } from './auth.service';
import { AlertService } from '../alert/alert.service';
import { first } from 'rxjs/operators';
import { MatSnackBar } from "@angular/material";

@Component({
  selector: 'app-password-reset',
  templateUrl: './password-reset.component.html',
  styleUrls: ['./password-reset.component.scss']
})
export class PasswordResetComponent implements OnInit {
  passwordResetForm: FormGroup;
  submitted = false;
  loading = false;
  returnUrl: string;
  userItems: any;

  constructor(
    private formBuilder: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private authenticationService: AuthService,
    private _snackBar: MatSnackBar,
    private alertService: AlertService
  ) {
    // redirect to home if already logged in
    if (this.authenticationService.currentUserValue) {
      this.router.navigate(['/']);
    }
  }

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem('currentUser'));
    return users;
  }

  ngOnInit() {
    this.passwordResetForm = this.formBuilder.group({
      username: ['', Validators.required]
    });
    this.returnUrl = '/login';
  }

  // convenience getter for easy access to form fields
  get f() {
    return this.passwordResetForm.controls;
  }

  async onSubmit() {
    this.submitted = true;
    if (this.passwordResetForm.invalid) {
      return;
    }

    this.loading = true;
    await this.authenticationService.resetPassword(this.passwordResetForm.get('username').value).then((res) => {
        this._snackBar.open("Password Reset OTP Sent", null, {
          duration: 10000,
          horizontalPosition: "center",
          panelClass: ["blue-snackbar"],
          verticalPosition: "top",
        });
    });
    this.loading = true;
    this.router.navigate([this.returnUrl]);
  }

  login() { }
}
