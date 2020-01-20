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
  public town_id = null;
  public time_id = null;
  public day_id = null;

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
    // this.destinationForm = this._formBuilder.group({
    //   town_id: ['', Validators.required],
    //   day_id: ['', Validators.required],
    //   time_id: ['', Validators.required]
    // });
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
    this.httpClient
      .post('/api/destination/time', {
        destination_id: this.town_id.ID,
        day_id: this.day_id.ID,
        time_id: this.time_id.ID
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
          window.location.reload();
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
