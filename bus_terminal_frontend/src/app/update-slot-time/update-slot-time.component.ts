import { Component, OnInit, Optional, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA, MatSnackBar } from '@angular/material';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute, Router } from '@angular/router';
// tslint:disable-next-line: quotemark
import { Location } from "@angular/common";

@Component({
  selector: 'app-update-slot-time',
  templateUrl: './update-slot-time.component.html',
  styleUrls: ['./update-slot-time.component.scss']
})
export class UpdateSlotTimeComponent implements OnInit {
  // Time formGroup
  timeForm: FormGroup;
  time_of_day: any;
  constructor(
    public dialogRef: MatDialogRef<UpdateSlotTimeComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: any,
    private _formBuilder: FormBuilder,
    private httpClient: HttpClient,
    private route: ActivatedRoute,
    private router: Router,
    private _snackBar: MatSnackBar,
    private _location: Location
  ) {
    // Time form Builder
    this.timeForm = this._formBuilder.group({
      time: ['', Validators.required]
    });
  }

  ngOnInit() {
    this.time_of_day = this.data.row.time;
  }

  update() {
    this.httpClient
      .put('/api/slots/update/' + this.data.row.ID, {
        time: this.time_of_day
        //    slot_one: this.data.row.slot_one,
        //    slot_two: this.data.row.slot_two,
        //    slot_three: this.data.row.three,
        //    slot_four: this.data.row.slot_four,
        //    slot_five: this.data.row.slot_five,
        //    reservation_time: this.data.row.reservation_time,
      })
      .subscribe(
        data => {
          this._location.back();
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
