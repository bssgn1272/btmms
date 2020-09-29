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

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
  loginForm: FormGroup;
  submitted = false;
  loading = false;
  returnUrl: string;
  userItems: any;
  error: string;

  constructor(
    private formBuilder: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private authenticationService: AuthService,
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
    this.loginForm = this.formBuilder.group({
      username: ['', Validators.required],
      password: ['', Validators.required]
    });
      this.returnUrl = this.route.snapshot.queryParams['returnUrl'] || '/dashboard';

  }

  // convenience getter for easy access to form fields
  get f() {
    return this.loginForm.controls;
  }

  onSubmit() {
    this.submitted = true;

    // stop here if form is invalid
    if (this.loginForm.invalid) {
      return;
    }

    this.error = "";
    this.loading = true;
    this.authenticationService
      .login(this.f.username.value, this.f.password.value)
      .pipe(first())
      .subscribe(
        data => {
          if(data.error === "Invalid password"){
            localStorage.removeItem('currentUser');
            console.log("Console Data>>> ", data);
            this.error = "Invalid Password"
            this.alertService.error("Invalid Username/Password");
            this.loading = false;
          } else if(data.error === "Invalid Username"){
            localStorage.removeItem('currentUser');
            console.log("Console Data>>> ", data);
            this.error = "Invalid Username"
            this.alertService.error("Invalid Username/Password");
            this.loading = false;
          }
          else{
            this.router.navigate([this.returnUrl]);
          }
        },
        error => {
          console.log(error);
          this.alertService.error(error);
          this.loading = false;
        }
      );
  }

  login() { }
}
