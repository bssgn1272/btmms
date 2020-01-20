import { Component, OnInit, ViewChild } from '@angular/core';
import { MatTableDataSource, MatPaginator, MatSort, MatSnackBar, MatDialog, MatDialogConfig } from '@angular/material';
import { SettingsService } from './settings.service';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { DestinationDayComponent } from '../destination-day/destination-day.component';
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-settings',
  templateUrl: './settings.component.html',
  styleUrls: ['./settings.component.scss']
})
export class SettingsComponent implements OnInit {
  // route decleration
  returnUrl: string;

  // Destination Time formGroup
  destinationForm: FormGroup;

  // Town formGroup
  townForm: FormGroup;

  // Day formGroup
  dayForm: FormGroup;

  // Time formGroup
  timeForm: FormGroup;

  // Destinations Time
  displayedColumns: string[] = ['destination', 'day', 'time'];
  dataSource = new MatTableDataSource([]);

  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;
  days: [];

  constructor(
    private settings: SettingsService,
    private _formBuilder: FormBuilder,
    private dialog: MatDialog,
    private httpClient: HttpClient,
    private route: ActivatedRoute,
    private router: Router,
    private _snackBar: MatSnackBar
  ) {
    // Destination Time form Builder
    this.destinationForm = this._formBuilder.group({
      town_name: ['', Validators.required],
      day: ['', Validators.required],
      time_of_day: ['', Validators.required]
    });

    // Destination Town form Builder
    this.townForm = this._formBuilder.group({
      town_name: ['', Validators.required]
    });

    // Day form Builder
    this.dayForm = this._formBuilder.group({
      day: ['', Validators.required]
    });

    // Time form Builder
    this.timeForm = this._formBuilder.group({
      time_of_day: ['', Validators.required]
    });
  }

  ngOnInit() {
    this.settings.getList().then(res => {
      this.dataSource = new MatTableDataSource(res.data);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    });

    // get return url from route parameters or default to '/'
    this.returnUrl = this.route.snapshot.queryParams['returnUrl'] || '/';
  }

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  // create destination time
  onOpenDialog() {
    const dialogConfig = new MatDialogConfig();
    // dialogConfig.disableClose = true;
    dialogConfig.autoFocus = true;
    dialogConfig.width = '60%';
    this.dialog.open(DestinationDayComponent, dialogConfig);
  }

  // convenience getter for easy access to form fields
  get f_town() {
    return this.townForm.controls;
  }

  get f_day() {
    return this.dayForm.controls;
  }

   get f_time() {
    return this.timeForm.controls;
  }

  // create town
  onSaveTown() {
    this.httpClient
      .post('/api/town', {
        town_name: this.f_town.town_name.value
      })
      .subscribe(
        data => {
          this.router.navigate([this.returnUrl]);
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

  // create day
  onSaveDay() {
    this.httpClient
      .post('/api/day', {
        day: this.f_day.day.value
      })
      .subscribe(
        data => {
          this.router.navigate([this.returnUrl]);
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

  // create time
  onSaveTime() {
    this.httpClient
      .post('/api/time', {
        time_of_day: this.f_time.time_of_day.value
      })
      .subscribe(
        data => {
          this.router.navigate([this.returnUrl]);
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
}
