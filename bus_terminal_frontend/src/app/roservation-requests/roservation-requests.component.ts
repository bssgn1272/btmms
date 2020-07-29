import { Component, OnInit, ViewChild } from "@angular/core";
import {
  MatTableDataSource,
  MatPaginator,
  MatSort,
  MatSnackBar,
  MatDialog,
} from "@angular/material";
import { ReservationRequestsService } from "./reservation-requests.service";
import { HttpClient } from "@angular/common/http";
import { Location, DatePipe, formatDate } from "@angular/common";
import { Router } from "@angular/router";
import { FormGroup, FormControl } from "@angular/forms";
import { OpenSlotsService } from "./slot.service";
import { RejectComponent } from "../reject/reject.component";
import * as moment from "moment";
import { CancellationRequestComponent } from "../cancellation-request/cancellation-request.component";
import { ConfirmCancellationComponent } from "../confirm-cancellation/confirm-cancellation.component";
import { SettingsService } from "app/settings/settings.service";

@Component({
  selector: "app-roservation-requests",
  templateUrl: "./roservation-requests.component.html",
  styleUrls: ["./roservation-requests.component.scss"],
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
  slot_six = "open";
  slot_seven = "open";
  slot_eight = "open";
  slot_nine = "open";
  user = "";
  time = "";
  returnUrl: string;
  inlineRange;
  selectedFilter = "";

  displayedColumns: string[] = [
    "username",
    "license_plate",
    "slot",
    "route",
    "time",
    "status",
    "reserved_time",
    "action",
  ];
  dataSource = new MatTableDataSource([]);
  filterDataSource: any;
  displayData: any;
  pipe: DatePipe;
  userItems: any;
  role: any;
  from: any;
  to: any;
  rStatus = "All";
  mode: any;
  workFlow: any;
  displayDataHistory: any;
  filterDataSourceHistory: any;
  fromHistory: any;
  toHistory: any;
  selectedHistoryFilter = "";

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem("currentUser"));
    return users;
  }

  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;
  slot_status: any;
  dataTable: any;
  dtOptions: any;

  displayedHistoryColumns: string[] = [
    "username",
    "license_plate",
    "slot",
    "route",
    "time",
    "status",
    "reserved_time",
  ];
  dataSourceHistory = new MatTableDataSource([]);

  @ViewChild("HistoryPaginator") paginatorHistory: MatPaginator;
  @ViewChild("HistorySort") sortHistory: MatSort;

  // Date Range

  // slots
  displayedSlotColumns: string[] = [
    "time",
    "slot_one",
    "slot_two",
    "slot_three",
    "slot_four",
    "slot_five",
    "slot_six",
    "slot_seven",
    "slot_eight",
    "slot_nine",
  ];

  dataSourceSlot = new MatTableDataSource([]);

  @ViewChild("slotPaginator") slotPaginator: MatPaginator;
  @ViewChild("slotSort") slotSort: MatSort;

  constructor(
    private requests: ReservationRequestsService,
    public _location: Location,
    private slots: OpenSlotsService,
    private dialog: MatDialog,
    private settings: SettingsService
  ) {
    this.pipe = new DatePipe("en");
    // console.log(this.dataSource.filterPredicate);
    // const defaultPredicate = this.dataSource.filterPredicate;
    // this.dataSource.filterPredicate = (data, filter) => {
    //   const formatted = this.pipe.transform(data.reserved_time, 'MM/dd/yyyy');
    //   return formatted.indexOf(filter) >= 0 || defaultPredicate(data, filter);
    // };
  }

  ngOnInit() {
    this.settings.getModes().then((res) => {
      this.mode = res.data;
      console.log(this.mode);
      this.workFlow = this.mode.filter((x) => x.status === "Active")[0];
    });
    this.userItems = this.getFromLocalStrorage();
    this.role = this.userItems.role;
    this.requests.getList().then((res) => {
      this.displayData = res.data;
      this.filterDataSource = this.displayData;
      this.dataSource = new MatTableDataSource(this.displayData);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    });

    this.requests.getHistoryList().then((res) => {
      this.displayDataHistory = res.data;
      this.filterDataSourceHistory = this.displayDataHistory;
      this.dataSourceHistory = new MatTableDataSource(this.displayDataHistory);
      this.dataSourceHistory.paginator = this.paginatorHistory;
      this.dataSourceHistory.sort = this.sortHistory;
    });

    // Slots
    this.slots.getList().then((res) => {
      this.dataSourceSlot = new MatTableDataSource(res.data);
      this.dataSourceSlot.paginator = this.slotPaginator;
      this.dataSourceSlot.sort = this.slotSort;
    });
  }

  log(value) {
    // this.requests.getList().then((res) => {
    if (value === "All") {
      this.filterDataSource = this.displayData;
      this.dataSource = new MatTableDataSource(this.filterDataSource);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    } else {
      this.filterDataSource = this.displayData.filter(
        (x) => x.status === value
      );
      this.dataSource = new MatTableDataSource(this.filterDataSource);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
      if (value === "All") {
        this.rStatus = "All";
      } else if (value === "C") {
        this.rStatus = "C";
      } else if (value === "A") {
        this.rStatus = "A";
      } else if (value === "PC") {
        this.rStatus = "PC";
      }
    }
    // });
  }

  dateRange() {
    console.log(this.from, this.to);
    if (this.rStatus !== "All") {
      this.filterDataSource = this.displayData.filter(
        (x) =>
          x.reserved_time >
            formatDate(this.from, "yyy-MM-dd hh:mm:ss", "en-US", "+0530") &&
          x.reserved_time <
            formatDate(this.to, "yyy-MM-dd hh:mm:ss", "en-US", "+0530") &&
          x.status === this.rStatus
      );
    } else {
      this.filterDataSource = this.displayData.filter(
        (x) =>
          x.reserved_time >
            formatDate(this.from, "yyy-MM-dd hh:mm:ss", "en-US", "+0530") &&
          x.reserved_time <
            formatDate(this.to, "yyy-MM-dd hh:mm:ss", "en-US", "+0530")
      );
    }

    this.dataSource = new MatTableDataSource(this.filterDataSource);
    this.dataSource.paginator = this.paginator;
    this.dataSource.sort = this.sort;
    console.log(
      this.displayData,
      formatDate(this.from, "yyy-MM-dd hh:mm:ss", "en-US", "+0530")
    );
  }

  logHistory(value) {
    // this.requests.getList().then((res) => {
    if (value === "All") {
      this.filterDataSourceHistory = this.displayDataHistory;
      this.dataSourceHistory = new MatTableDataSource(
        this.filterDataSourceHistory
      );
      this.dataSourceHistory.paginator = this.paginatorHistory;
      this.dataSourceHistory.sort = this.sortHistory;
    } else {
      this.filterDataSourceHistory = this.displayDataHistory.filter(
        (x) => x.status === value
      );
      this.dataSourceHistory = new MatTableDataSource(
        this.filterDataSourceHistory
      );
      this.dataSourceHistory.paginator = this.paginatorHistory;
      this.dataSourceHistory.sort = this.sortHistory;
      if (value === "All") {
        this.rStatus = "All";
      } else if (value === "C") {
        this.rStatus = "C";
      } else if (value === "A") {
        this.rStatus = "A";
      } else if (value === "PC") {
        this.rStatus = "PC";
      }
    }
    // });
  }

  dateHistoryRange() {
    console.log(this.fromHistory, this.toHistory);
    if (this.rStatus !== "All") {
      this.filterDataSourceHistory = this.displayDataHistory.filter(
        (x) =>
          x.reserved_time >
            formatDate(
              this.fromHistory,
              "yyy-MM-dd hh:mm:ss",
              "en-US",
              "+0530"
            ) &&
          x.reserved_time <
            formatDate(
              this.toHistory,
              "yyy-MM-dd hh:mm:ss",
              "en-US",
              "+0530"
            ) &&
          x.status === this.rStatus
        // &&
        // x.status === this.rStatus
      );
    } else {
      this.filterDataSourceHistory = this.displayDataHistory.filter(
        (x) =>
          x.reserved_time >
            formatDate(
              this.fromHistory,
              "yyy-MM-dd hh:mm:ss",
              "en-US",
              "+0530"
            ) &&
          x.reserved_time <
            formatDate(this.toHistory, "yyy-MM-dd hh:mm:ss", "en-US", "+0530")
      );
    }

    this.dataSourceHistory = new MatTableDataSource(
      this.filterDataSourceHistory
    );
    this.dataSourceHistory.paginator = this.paginatorHistory;
    this.dataSourceHistory.sort = this.sortHistory;
    console.log(
      this.displayDataHistory,
      formatDate(this.fromHistory, "yyy-MM-dd hh:mm:ss", "en-US", "+0530")
    );
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

  applyHistoryFilter(filterValue: string) {
    this.dataSourceHistory.filter = filterValue.trim().toLowerCase();
    console.log(this.dataSourceHistory);
    console.log(filterValue);
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
      width: "60%",
      // height: "850",
      data: { row },
    });
    dialogRef.afterClosed().subscribe((result) => {
      row = result;
    });
    console.log("Row clicked: ", row);
  }

  makeCancellationRequest(row): void {
    const dialogRef = this.dialog.open(CancellationRequestComponent, {
      width: "60%",
      // height: "850",
      data: { row },
    });
    dialogRef.afterClosed().subscribe((result) => {
      row = result;
      console.log(row);
    });
  }

  confirmCancellationRequest(row): void {
    const dialogRef = this.dialog.open(ConfirmCancellationComponent, {
      width: "60%",
      // height: "850",
      data: { row },
    });
    dialogRef.afterClosed().subscribe((result) => {
      row = result;
      console.log(row);
    });
  }
}
