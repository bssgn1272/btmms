import { Component, OnInit } from '@angular/core';
import { MatDialogRef, MatSnackBar } from '@angular/material';
import { FormGroup, Validators, FormBuilder } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute, Router } from '@angular/router';
import * as moment from 'moment';

@Component({
  selector: 'app-slot-time',
  templateUrl: './slot-time.component.html',
  styleUrls: ['./slot-time.component.scss']
})
export class SlotTimeComponent implements OnInit {
  // Time formGroup
  timeForm: FormGroup;
  today = new Date();
  tomorrow = new Date();
  constructor(
    public dialogRef: MatDialogRef<SlotTimeComponent>,
    private _formBuilder: FormBuilder,
    private httpClient: HttpClient,
    private route: ActivatedRoute,
    private router: Router,
    private _snackBar: MatSnackBar
  ) {
    // Time form Builder
    this.timeForm = this._formBuilder.group({
      time: ['', Validators.required]
    });
  }

  ngOnInit() {
    moment(this.tomorrow.setDate(this.today.getDate() + 1)
    );
    console.log(this.tomorrow);
  }

  get f_time() {
    return this.timeForm.controls;
  }

  save() {
    console.log(this.f_time);

    this.httpClient
      .post('/api/slots/create', {
        time: this.f_time.time.value,
        slot_one: 'open',
        slot_two: 'open',
        slot_three: 'open',
        slot_four: 'open',
        slot_five: 'open',
        reservation_time: this.tomorrow
      })
      .subscribe(
        data => {
          window.location.reload();
          this._snackBar.open('Successfully Created', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
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
