import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { MatTableDataSource, MatPaginator, MatSort, MatSnackBar } from '@angular/material';
import { ReservationRequestsService } from './reservation-requests.service';
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute, Router } from '@angular/router';
import { reject } from 'q';

@Component({
  selector: 'app-roservation-requests',
  templateUrl: './roservation-requests.component.html',
  styleUrls: ['./roservation-requests.component.scss']
})
export class RoservationRequestsComponent implements OnInit {
  status = '';
  id = 0;
  slot = '';
  slot_one = 'open';
  slot_two = 'open';
  slot_three = 'open';
  slot_four = 'open';
  slot_five = 'open';
  user = '';
  time = '';
  returnUrl: string;
  // Roservation Requests
  displayedColumns: string[] = [
    'username',
    'slot',
    'route',
    'time',
    'status',
    'action'
  ];
  dataSource = new MatTableDataSource([]);

  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;

  constructor(
    private el: ElementRef,
    private requests: ReservationRequestsService,
    private httpClient: HttpClient,
    private route: ActivatedRoute,
    private router: Router,
    private _snackBar: MatSnackBar
  ) {}

  ngOnInit() {
    this.requests.getList().then(res => {
      this.dataSource = new MatTableDataSource(res.data);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    });
  }

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  approve(row) {
    this.slot = row.slot;
    this.id = row.r_id;
    this.status = 'A';
    this.time = row.time;
    this.user = row.username;
    if (this.slot === 'slot_one') {
      this.httpClient
        .put('/api/slots/close', {
          time: this.time,
          slot_one: this.user
        })
        .toPromise();
    }
    if (this.slot === 'slot_two') {
      this.httpClient
        .put('/api/slots/close', {
          time: this.time,
          slot_two: this.user
        })
        .toPromise();
    }
    if (this.slot === 'slot_three') {
      this.httpClient
        .put('/api/slots/close', {
          time: this.time,
          slot_three: this.user
        })
        .toPromise();
    }
    if (this.slot === 'slot_four') {
      this.httpClient
        .put('/api/slots/close', {
          time: this.time,
          slot_four: this.user
        })
        .toPromise();
    }
    if (this.slot === 'slot_five') {
      this.httpClient
        .put('/api/slots/close', {
          time: this.time,
          slot_five: this.user
        })
        .toPromise();
    }
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
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
    console.log(row);
  }
  reject(row) {
    this.slot = row.username;
    this.id = row.r_id;
    this.status = 'R';
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
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
    console.log(row);
  }
}
