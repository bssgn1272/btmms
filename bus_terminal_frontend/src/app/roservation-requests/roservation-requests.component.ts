import { Component, OnInit, ViewChild } from '@angular/core';
import {
  MatTableDataSource,
  MatPaginator,
  MatSort,
  MatSnackBar,
  MatDialog} from '@angular/material';
import { ReservationRequestsService } from './reservation-requests.service';
import { HttpClient } from '@angular/common/http';
import { Location, DatePipe, formatDate } from '@angular/common';
import { Router } from '@angular/router';
import { FormGroup, FormControl } from '@angular/forms';
import { OpenSlotsService } from './slot.service';
import { RejectComponent } from '../reject/reject.component';
import * as moment from 'moment';

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
  inlineRange;

  displayedColumns: string[] = [
    'username',
    'slot',
    'route',
    'time',
    'status',
    'reserved_time',
    'action'
  ];
  dataSource = new MatTableDataSource([]);
  pipe: DatePipe;

  // filterForm = new FormGroup({
  //   fromDate: new FormControl(),
  //   toDate: new FormControl()
  // });
  // datas: any;

  // get fromDate() {
  //   return this.filterForm.get('fromDate');
  // }
  // get toDate() {
  //   return this.filterForm.get('toDate');
  // }

  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;
  slot_status: any;
  dataTable: any;
  dtOptions: any;

  // Date Range

  // slots
  displayedSlotColumns: string[] = [
    'time',
    'slot_one',
    'slot_two',
    'slot_three',
    'slot_four',
    'slot_five'
  ];
  dataSourceSlot = new MatTableDataSource([]);

  @ViewChild('slotPaginator') slotPaginator: MatPaginator;
  @ViewChild('slotSort') slotSort: MatSort;

  constructor(
    private requests: ReservationRequestsService,
    public _location: Location,
    private slots: OpenSlotsService,
    private dialog: MatDialog
  ) {
    // this.pipe = new DatePipe('en');
    // console.log(this.dataSource.filterPredicate);
    // const defaultPredicate = this.dataSource.filterPredicate;
    // this.dataSource.filterPredicate = (data, filter) => {
    //   const formatted = this.pipe.transform(data.reserved_time, 'MM/dd/yyyy');
    //   return formatted.indexOf(filter) >= 0 || defaultPredicate(data, filter);
    // };
  }

  ngOnInit() {
    this.requests.getList().then(res => {
      this.dataSource = new MatTableDataSource(res.data);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    });

    // Slots
    this.slots.getList().then(res => {
      this.dataSourceSlot = new MatTableDataSource(res.data);
      this.dataSourceSlot.paginator = this.slotPaginator;
      this.dataSourceSlot.sort = this.slotSort;
    });
  }

  inlineRangeChange($event) {
    this.inlineRange = $event;
  }

  // getDateRange(value) {
  //     this.dataSource.data = this.datas;
  //     const fromDate = value.fromDate;
  //     const toDate = value.toDate;
  //     this.dataSource.data = this.dataSource.data.filter(
  //       e => e.reserved_time > fromDate && e.reserved_time < toDate
  //     );
  //     console.log(fromDate, toDate, this.datas, this.dataSource.data);
  // }

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
    console.log(this.dataSource);
  }

  applySlotFilter(filterValue: string) {
    this.dataSourceSlot.filter = filterValue.trim().toLowerCase();
  }

  // applyDateFilter() {
  //   this.dataSource.filter = "" + Math.random();
  //   console.log(this.dataSource);
  // }

  // add Open Dialog
  onOpenRejectDialog(row): void {
    const dialogRef = this.dialog.open(RejectComponent, {
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
