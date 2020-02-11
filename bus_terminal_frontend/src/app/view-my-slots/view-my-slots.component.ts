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
import { Location } from '@angular/common';
import { CancelReservationComponent } from '../cancel-reservation/cancel-reservation.component';



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
    private authenticationService: AuthService,
    private _location: Location,
    private dialog: MatDialog
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

  // add Open Dialog
  onOpenCancelDialog(row): void {
    const dialogRef = this.dialog.open(CancelReservationComponent, {
      width: '60%',
      // height: "850",
      data: { row }
    });
    dialogRef.afterClosed().subscribe(result => {
      row = result;
    });
    console.log('Row clicked: ', row);
  }
}

