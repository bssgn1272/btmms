import { Component, OnInit, ViewChild } from '@angular/core';
import {
  MatTableDataSource,
  MatPaginator,
  MatDialog,
  MatDialogConfig,
  MatSort,
  MatSnackBar
} from '@angular/material';
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute, Router } from '@angular/router';
import { ViewSlotsService } from './view-slots.service';
import { AuthService } from 'app/login/auth.service';



@Component({
  selector: 'app-view-my-slots',
  templateUrl: './view-my-slots.component.html',
  styleUrls: ['./view-my-slots.component.scss']
})
export class ViewMySlotsComponent implements OnInit {
  status = '';
  id = 0;
  slot = '';
  slot_one = 'open';
  slot_two = 'open';
  slot_three = 'open';
  slot_four = 'open';
  slot_five = 'open';
  time = '';
  displayedColumns: string[] = [
    'time',
    'slot',
    'route',
    'status',
    'reserved_time',
    'action'
  ];

  dataSource = new MatTableDataSource([]);

  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;
  userItems: any;

  constructor(
    private httpClient: HttpClient,
    private route: ActivatedRoute,
    private router: Router,
    private _snackBar: MatSnackBar,
    private viewSlots: ViewSlotsService,
    private authenticationService: AuthService
  ) {}

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem('currentUser'));
    return users;
  }

  ngOnInit() {
    this.userItems = this.getFromLocalStrorage();
    const _id = this.userItems.ID;

    this.viewSlots.getList(_id).then(res => {
      this.dataSource = new MatTableDataSource(res.data);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    });

    // console.log(this.currentUser.id);
  }

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  cancel(row) {
    this.slot = row.slot;
    this.id = row.ID;
    this.status = 'C';
    this.time = row.time;
    if (this.slot === 'slot_one') {
      this.httpClient
        .put('/api/slots/close', {
          time: this.time,
          slot_one: this.slot_one
        })
        .toPromise();
    } else if (this.slot === 'slot_two') {
      this.httpClient
        .put('/api/slots/close', {
          time: this.time,
          slot_two: this.slot_two
        })
        .toPromise();
    } else if (this.slot === 'slot_three') {
      this.httpClient
        .put('/api/slots/close', {
          time: this.time,
          slot_three: this.slot_three
        })
        .toPromise();
    } else if (this.slot === 'slot_four') {
      this.httpClient
        .put('/api/slots/close', {
          time: this.time,
          slot_four: this.slot_four
        })
        .toPromise();
    } else if (this.slot === 'slot_five') {
      this.httpClient
        .put('/api/slots/close', {
          time: this.time,
          slot_five: this.slot_five
        })
        .toPromise();
    }
    console.log(this.id);
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          // window.location.reload();
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
  }
}

