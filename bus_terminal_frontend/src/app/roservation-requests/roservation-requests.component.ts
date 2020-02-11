import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import {
  MatTableDataSource,
  MatPaginator,
  MatSort,
  MatSnackBar,
  MatDialog,
  MatDialogConfig
} from '@angular/material';
import { ReservationRequestsService } from './reservation-requests.service';
import { HttpClient } from '@angular/common/http';
import { Location, DatePipe } from '@angular/common';
import { ActivatedRoute, Router } from '@angular/router';
import { FormGroup, FormBuilder, FormControl } from '@angular/forms';
import { OpenSlotsService } from './slot.service';
import { RejectComponent } from '../reject/reject.component';

@Component({
  selector: "app-roservation-requests",
  templateUrl: "./roservation-requests.component.html",
  styleUrls: ["./roservation-requests.component.scss"]
})
export class RoservationRequestsComponent implements OnInit {
  status = "";
  id = 0;
  slot = "";
  slot_one = "open";
  slot_two = "open";
  slot_three = "open";
  slot_four = "open";
  slot_five = "open";
  user = "";
  time = "";
  returnUrl: string;
  inlineRange;

  // filterForm = new FormGroup({
  //   fromDate: new FormControl(),
  //   toDate: new FormControl()
  // });
  // pipe: DatePipe;

  // get fromDate() {
  //   return this.filterForm.get("fromDate").value;
  // }
  // get toDate() {
  //   return this.filterForm.get("toDate").value;
  // }
  // Roservation Requests
  displayedColumns: string[] = [
    "username",
    "slot",
    "route",
    "time",
    "status",
    "reserved_time",
    "action"
  ];
  dataSource = new MatTableDataSource([]);

  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;
  slot_status: any;

  // Date Range
  pipe: DatePipe;

  filterForm = new FormGroup({
    fromDate: new FormControl(),
    toDate: new FormControl()
  });
  dataNewSource: any;

  get fromDate() {
    return this.filterForm.get("fromDate").value;
  }
  get toDate() {
    return this.filterForm.get("toDate").value;
  }

  // slots
  displayedSlotColumns: string[] = [
    "time",
    "slot_one",
    "slot_two",
    "slot_three",
    "slot_four",
    "slot_five"
  ];
  dataSourceSlot = new MatTableDataSource([]);

  @ViewChild("slotPaginator") slotPaginator: MatPaginator;
  @ViewChild("slotSort") slotSort: MatSort;

  constructor(
    private requests: ReservationRequestsService,
    private httpClient: HttpClient,
    private router: Router,
    private _snackBar: MatSnackBar,
    public _location: Location,
    private slots: OpenSlotsService,
    private dialog: MatDialog
  ) {
    // this.pipe = new DatePipe('en');
    // this.dataSource.filterPredicate = (data, filter) => {
    //   if (this.fromDate && this.toDate) {
    //     return (
    //       data.reserved_time >= this.fromDate &&
    //       data.reserved_time <= this.toDate
    //     );
    //   }
    //   return true;
    // };

    this.pipe = new DatePipe("en");
    console.log(this.dataSource.filterPredicate);
    const defaultPredicate = this.dataSource.filterPredicate;
    this.dataSource.filterPredicate = (data, filter) => {
      const formatted = this.pipe.transform(data.created, "MM/dd/yyyy");
      return formatted.indexOf(filter) >= 0 || defaultPredicate(data, filter);
    };
  }

  ngOnInit() {
    this.requests.getList().then(res => {
      this.dataSource = new MatTableDataSource(res.data);
      this.dataNewSource = res.data;
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

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  applySlotFilter(filterValue: string) {
    this.dataSourceSlot.filter = filterValue.trim().toLowerCase();
  }

  applyDateFilter() {
    this.dataSource.filter = "" + Math.random();
  }

  getDateRange(value) {
    // getting date from calendar
    console.log(this.dataNewSource);
    const fromDate = value.fromDate;
    const toDate = value.toDate;
    this.dataNewSource = this.dataNewSource
      .filter(e => e.reserved_time > fromDate && e.reserved_time < toDate)
      .sort((a, b) => a.reserved_time - b.reserved_time);
    console.log(fromDate, toDate, this.dataNewSource);
  }

  // add Open Dialog
  onOpenRejectDialog(row): void {
    const dialogRef = this.dialog.open(RejectComponent, {
      width: "60%",
      // height: "850",
      data: { row }
    });
    dialogRef.afterClosed().subscribe(result => {
      row = result;
    });
    console.log("Row clicked: ", row);
  }
}
