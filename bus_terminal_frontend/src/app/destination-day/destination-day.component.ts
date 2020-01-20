import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { MatDialogRef, MatSnackBar } from '@angular/material';
import { HttpClient } from '@angular/common/http';
import { DestinationDayService } from './destination-day.service';
import { ActivatedRoute, Router } from '@angular/router';


@Component({
  selector: 'app-destination-day',
  templateUrl: './destination-day.component.html',
  styleUrls: ['./destination-day.component.scss']
})
export class DestinationDayComponent implements OnInit {
  // Declerations
  public towns: [];
  public days: [];
  public times: [];
  public town_id = 0;

  // Destination formGroup
  destinationForm: FormGroup;
  constructor(
    private _formBuilder: FormBuilder,
    public dialogRef: MatDialogRef<DestinationDayComponent>,
    private httpClient: HttpClient,
    private destinationTime: DestinationDayService,
    private _snackBar: MatSnackBar
  ) {
    // Destination form Builder
    this.destinationForm = this._formBuilder.group({
      town_id: ['', Validators.required],
      day_id: ['', Validators.required],
      time_id: ['', Validators.required]
    });
  }

  async ngOnInit() {
    this.loadTown();
    this.loadDay();
    this.loadTime();
  }

  // fetch towns
  loadTown() {
    this.destinationTime.getList().then(res => {
      this.towns = res.data;
    });
  }

  // fetch day
  loadDay() {
    this.destinationTime.getDays().then(res => {
      this.days = res.data;
    });
  }

  // fetch times
  loadTime() {
     this.destinationTime.getTimes().then(res => {
       this.times = res.data;
     });
  }

  // convenience getter for easy access to form fields
  get f() {
    return this.destinationForm.controls;
  }


  save() {

    console.log(this.f.town_id.value);
    console.log(this.town_id);
    this.httpClient
      .post('/api/destination/time', {
        destination_id: this.f.town_id.value,
        day_id: this.f.day_id.value,
        time_id: this.f.time_id.value
      })
      .subscribe(
        data => {
          // window.location.reload();
          this._snackBar.open('Successfully Created', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
          this.dialogRef.close();
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  close() {
    this.dialogRef.close();
  }
}
