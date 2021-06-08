import { Component, OnInit, ViewChild } from '@angular/core';
import {
  MatTableDataSource,
  MatPaginator,
  MatSort,
  MatSnackBar,
  MatDialog,
} from '@angular/material';
import { ReservationRequestsService } from './reservation-requests.service';
import { HttpClient } from '@angular/common/http';
import { Location, DatePipe, formatDate } from '@angular/common';
import { Router } from '@angular/router';
import { FormGroup, FormControl } from '@angular/forms';
import { OpenSlotsService } from './slot.service';
import { RejectComponent } from '../reject/reject.component';
import { RejectArrivalComponent } from '../reject-arrival/reject-arrival.component';
import * as moment from 'moment';
import { CancellationRequestComponent } from '../cancellation-request/cancellation-request.component';
import { ConfirmCancellationComponent } from '../confirm-cancellation/confirm-cancellation.component';
import { SettingsService } from 'app/settings/settings.service';
import {ApproveReservationComponent} from '../approve-reservation/approve-reservation.component';
import {ApproveArrivalReservationComponent} from '../approve-arrival-reservation/approve-arrival-reservation.component';
import {CancelArrivalReservationComponent} from '../cancel-arrival-reservation/cancel-arrival-reservation.component';
import { CancelReservationComponent } from 'app/cancel-reservation/cancel-reservation.component';

@Component({
  selector: 'app-roservation-requests',
  templateUrl: './roservation-requests.component.html',
  styleUrls: ['./roservation-requests.component.scss'],
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
  slot_six = 'open';
  slot_seven = 'open';
  slot_eight = 'open';
  slot_nine = 'open';
  user = '';
  time = '';
  returnUrl: string;
  inlineRange;
  selectedFilter = '';
  selectedFilterAR = '';

  displayedColumns: string[] = [
    'username',
    'license_plate',
    'slot',
    'route',
    'time',
    'status',
    'reserved_time',
    'action',
  ];

  displayedColumnsAR: string[] = [
    'username',
    'license_plate',
    'slot',
    'route',
    'time',
    'status',
    'reserved_time',
    'action',
  ];
  dataSource = new MatTableDataSource([]);
  filterDataSource: any;
  dataSourceAR = new MatTableDataSource([]);


  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;

  @ViewChild('MatPaginatorAR') paginatorAR: MatPaginator;
  @ViewChild('MatSortAR') sortAR: MatSort;
  slot_status: any;
  dataTable: any;
  dtOptions: any;

  displayedHistoryColumns: string[] = [
    'username',
    'license_plate',
    'slot',
    'route',
    'time',
    'status',
    'reserved_time',
  ];
  dataSourceHistory = new MatTableDataSource([]);

  @ViewChild('HistoryPaginator') paginatorHistory: MatPaginator;
  @ViewChild('HistorySort') sortHistory: MatSort;

  displayedHistoryColumnsAR: string[] = [
    'username',
    'license_plate',
    'slot',
    'route',
    'time',
    'status',
    'reserved_time',
  ];
  dataSourceHistoryAR = new MatTableDataSource([]);

  @ViewChild('HistoryPaginatorAR') paginatorHistoryAR: MatPaginator;
  @ViewChild('HistorySortAR') sortHistoryAR: MatSort;

  filterDataSourceAR: any;
  displayData: any;
  displayDataAR: any;
  pipe: DatePipe;
  userItems: any;
  role: any;
  from: any;
  to: any;
  fromAR: any;
  toAR: any;
  rStatus = 'All';
  aRRStatus = 'All';
  mode: any;
  workFlow: any;
  displayDataHistory: any;
  filterDataSourceHistory: any;
  fromHistory: any;
  toHistory: any;
  selectedHistoryFilter = '';
  displayDataHistoryAR: any;
  filterDataSourceHistoryAR: any;
  fromHistoryAR: any;
  toHistoryAR: any;
  selectedHistoryFilterAR = '';

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem('currentUser'));
    return users;
  }


  // Date Range

  // slots
  // displayedSlotColumns: string[] = [
  //   'time',
  //   'slot_one',
  //   'slot_two',
  //   'slot_three',
  //   'slot_four',
  //   'slot_five',
  //   'slot_six',
  //   'slot_seven',
  //   'slot_eight',
  //   'slot_nine',
  // ];

  // dataSourceSlot = new MatTableDataSource([]);
  //
  // @ViewChild('slotPaginator') slotPaginator: MatPaginator;
  // @ViewChild('slotSort') slotSort: MatSort;

  // dataSourceSlotAR = new MatTableDataSource([]);
  //
  // @ViewChild('slotPaginatorAR') slotPaginatorAR: MatPaginator;
  // @ViewChild('slotSortAR') slotSortAR: MatSort;

  constructor(
    private requests: ReservationRequestsService,
    public _location: Location,
    private slots: OpenSlotsService,
    private dialog: MatDialog,
    private settings: SettingsService
  ) {
    this.pipe = new DatePipe('en');
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
      this.workFlow = this.mode.filter((x) => x.status === 'Active')[0];
    });
    this.userItems = this.getFromLocalStrorage();
    this.role = this.userItems.role;
    this.requests.getList().then((res) => {
      console.log("WTWTWTWTWTWTWTW", res)
      this.displayData = res.data;
      this.filterDataSource = this.displayData;
      this.dataSource = new MatTableDataSource(this.displayData);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    });


    this.requests.getARList().then((res) => {
      this.displayDataAR = res.data;
      this.filterDataSourceAR = this.displayDataAR;
      this.dataSourceAR = new MatTableDataSource(this.displayDataAR);
      this.dataSourceAR.paginator = this.paginatorAR;
      this.dataSourceAR.sort = this.sortAR;
    });

    this.requests.getHistoryList().then((res) => {
      this.displayDataHistory = res.data;
      this.filterDataSourceHistory = this.displayDataHistory;
      this.dataSourceHistory = new MatTableDataSource(this.displayDataHistory);
      this.dataSourceHistory.paginator = this.paginatorHistory;
      this.dataSourceHistory.sort = this.sortHistory;
    });


    this.requests.getARHistoryList().then((res) => {
      this.displayDataHistoryAR = res.data;
      this.filterDataSourceHistoryAR = this.displayDataHistoryAR;
      this.dataSourceHistoryAR = new MatTableDataSource(this.displayDataHistoryAR);
      this.dataSourceHistoryAR.paginator = this.paginatorHistoryAR;
      this.dataSourceHistoryAR.sort = this.sortHistoryAR;
    });

    // Slots
    // this.slots.getList().then((res) => {
    //   this.dataSourceSlot = new MatTableDataSource(res.data);
    //   this.dataSourceSlot.paginator = this.slotPaginator;
    //   this.dataSourceSlot.sort = this.slotSort;
    // });
  }

  log(value) {
    // this.requests.getList().then((res) => {
    if (value === 'All') {
      this.filterDataSource = this.displayData;
      this.dataSource = new MatTableDataSource(this.filterDataSource);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    } else {
      this.filterDataSource = this.displayData.filter(
        (x) => x.reservation_status === value
      );
      this.dataSource = new MatTableDataSource(this.filterDataSource);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
      if (value === 'All') {
        this.rStatus = 'All';
      } else if (value === 'C') {
        this.rStatus = 'C';
      } else if (value === 'A') {
        this.rStatus = 'A';
      } else if (value === 'PC') {
        this.rStatus = 'PC';
      } else if (value === 'P') {
        this.rStatus = 'P';
      }
    }
    // });
  }


  logAR(value) {
    // this.requests.getList().then((res) => {
    if (value === 'All') {
      this.filterDataSourceAR = this.displayData;
      this.dataSourceAR = new MatTableDataSource(this.filterDataSourceAR);
      this.dataSourceAR.paginator = this.paginatorAR;
      this.dataSourceAR.sort = this.sortAR;
    } else {
      this.filterDataSourceAR = this.displayDataAR.filter(
          (x) => x.reservation_status === value
      );
      this.dataSourceAR = new MatTableDataSource(this.filterDataSourceAR);
      this.dataSourceAR.paginator = this.paginatorAR;
      this.dataSourceAR.sort = this.sortAR;
      if (value === 'All') {
        this.aRRStatus = 'All';
      } else if (value === 'C') {
        this.aRRStatus = 'C';
      } else if (value === 'A') {
        this.aRRStatus = 'A';
      } else if (value === 'PC') {
        this.aRRStatus = 'PC';
      } else if (value === 'P') {
        this.aRRStatus = 'P';
      }
    }
    // });
  }

  dateRange() {
    console.log(this.from, this.to);
    if (this.rStatus !== 'All') {
      this.filterDataSource = this.displayData.filter(
        (x) =>
          x.reserved_time >
            formatDate(this.from, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530') &&
          x.reserved_time <
            formatDate(this.to, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530') &&
          x.reservation_status === this.rStatus
      );
    } else {
      this.filterDataSource = this.displayData.filter(
        (x) =>
          x.reserved_time >
            formatDate(this.from, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530') &&
          x.reserved_time <
            formatDate(this.to, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530')
      );
    }

    this.dataSource = new MatTableDataSource(this.filterDataSource);
    this.dataSource.paginator = this.paginator;
    this.dataSource.sort = this.sort;
    console.log(
      this.displayData,
      formatDate(this.from, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530')
    );
  }



  dateRangeAR() {
    console.log(this.from, this.to);
    if (this.aRRStatus !== 'All') {
      this.filterDataSourceAR = this.displayDataAR.filter(
          (x) =>
              x.reserved_time >
              formatDate(this.from, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530') &&
              x.reserved_time <
              formatDate(this.to, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530') &&
              x.reservation_status === this.rStatus
      );
    } else {
      this.filterDataSourceAR = this.displayDataAR.filter(
          (x) =>
              x.reserved_time >
              formatDate(this.from, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530') &&
              x.reserved_time <
              formatDate(this.to, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530')
      );
    }

    this.dataSourceAR = new MatTableDataSource(this.filterDataSourceAR);
    this.dataSourceAR.paginator = this.paginatorAR;
    this.dataSourceAR.sort = this.sortAR;
    console.log(
        this.displayDataAR,
        formatDate(this.from, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530')
    );
  }

  logHistory(value) {
    // this.requests.getList().then((res) => {
    if (value === 'All') {
      this.filterDataSourceHistory = this.displayDataHistory;
      this.dataSourceHistory = new MatTableDataSource(
        this.filterDataSourceHistory
      );
      this.dataSourceHistory.paginator = this.paginatorHistory;
      this.dataSourceHistory.sort = this.sortHistory;
    } else {
      this.filterDataSourceHistory = this.displayDataHistory.filter(
        (x) => x.reservation_status === value
      );
      this.dataSourceHistory = new MatTableDataSource(
        this.filterDataSourceHistory
      );
      this.dataSourceHistory.paginator = this.paginatorHistory;
      this.dataSourceHistory.sort = this.sortHistory;
      if (value === 'All') {
        this.rStatus = 'All';
      } else if (value === 'C') {
        this.rStatus = 'C';
      } else if (value === 'A') {
        this.rStatus = 'A';
      } else if (value === 'PC') {
        this.rStatus = 'PC';
      }
    }
    // });
  }



  logHistoryAR(value) {
    // this.requests.getList().then((res) => {
    if (value === 'All') {
      this.filterDataSourceHistoryAR = this.displayDataHistoryAR;
      this.dataSourceHistoryAR = new MatTableDataSource(
          this.filterDataSourceHistoryAR
      );
      this.dataSourceHistoryAR.paginator = this.paginatorHistoryAR;
      this.dataSourceHistoryAR.sort = this.sortHistoryAR;
    } else {
      this.filterDataSourceHistoryAR = this.displayDataHistoryAR.filter(
          (x) => x.reservation_status === value
      );
      this.dataSourceHistoryAR = new MatTableDataSource(
          this.filterDataSourceHistoryAR
      );
      this.dataSourceHistoryAR.paginator = this.paginatorHistoryAR;
      this.dataSourceHistoryAR.sort = this.sortHistoryAR;
      if (value === 'All') {
        this.aRRStatus = 'All';
      } else if (value === 'C') {
        this.aRRStatus = 'C';
      } else if (value === 'A') {
        this.aRRStatus = 'A';
      } else if (value === 'PC') {
        this.aRRStatus = 'PC';
      }
    }
    // });
  }

  dateHistoryRange() {
    console.log(this.fromHistory, this.toHistory);
    if (this.rStatus !== 'All') {
      this.filterDataSourceHistory = this.displayDataHistory.filter(
        (x) =>
          x.reserved_time >
            formatDate(
              this.fromHistory,
              'yyy-MM-dd hh:mm:ss',
              'en-US',
              '+0530'
            ) &&
          x.reserved_time <
            formatDate(
              this.toHistory,
              'yyy-MM-dd hh:mm:ss',
              'en-US',
              '+0530'
            ) &&
          x.reservation_status === this.rStatus
        // &&
        // x.status === this.rStatus
      );
    } else {
      this.filterDataSourceHistory = this.displayDataHistory.filter(
        (x) =>
          x.reserved_time >
            formatDate(
              this.fromHistory,
              'yyy-MM-dd hh:mm:ss',
              'en-US',
              '+0530'
            ) &&
          x.reserved_time <
            formatDate(this.toHistory, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530')
      );
    }

    this.dataSourceHistory = new MatTableDataSource(
      this.filterDataSourceHistory
    );
    this.dataSourceHistory.paginator = this.paginatorHistory;
    this.dataSourceHistory.sort = this.sortHistory;
    console.log(
      this.displayDataHistory,
      formatDate(this.fromHistory, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530')
    );
  }




  dateHistoryRangeAR() {
    console.log(this.fromHistoryAR, this.toHistoryAR);
    if (this.aRRStatus !== 'All') {
      this.filterDataSourceHistoryAR = this.displayDataHistoryAR.filter(
          (x) =>
              x.reserved_time >
              formatDate(
                  this.fromHistoryAR,
                  'yyy-MM-dd hh:mm:ss',
                  'en-US',
                  '+0530'
              ) &&
              x.reserved_time <
              formatDate(
                  this.toHistoryAR,
                  'yyy-MM-dd hh:mm:ss',
                  'en-US',
                  '+0530'
              ) &&
              x.reservation_status === this.rStatus
          // &&
          // x.status === this.rStatus
      );
    } else {
      this.filterDataSourceHistoryAR = this.displayDataHistoryAR.filter(
          (x) =>
              x.reserved_time >
              formatDate(
                  this.fromHistoryAR,
                  'yyy-MM-dd hh:mm:ss',
                  'en-US',
                  '+0530'
              ) &&
              x.reserved_time <
              formatDate(this.toHistoryAR, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530')
      );
    }

    this.dataSourceHistoryAR = new MatTableDataSource(
        this.filterDataSourceHistoryAR
    );
    this.dataSourceHistoryAR.paginator = this.paginatorHistoryAR;
    this.dataSourceHistoryAR.sort = this.sortHistoryAR;
    console.log(
        this.displayDataHistoryAR,
        formatDate(this.fromHistoryAR, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530')
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


  applyFilterAR(filterValue: string) {
    this.dataSourceAR.filter = filterValue.trim().toLowerCase();
    console.log(this.dataSourceAR);
  }

  applyHistoryFilterAR(filterValue: string) {
    this.dataSourceHistoryAR.filter = filterValue.trim().toLowerCase();
  }











  // applySlotFilter(filterValue: string) {
  //   this.dataSourceSlot.filter = filterValue.trim().toLowerCase();
  // }

  // applyDateFilter() {
  //   this.dataSource.filter = "" + Math.random();
  //   console.log(this.dataSource);
  // }


  // add Open Dialog
  onOpenRejectDialog(row): void {
    const dialogRef = this.dialog.open(RejectComponent, {
      width: '60%',
      // height: "850",
      data: { row },
    });
    dialogRef.afterClosed().subscribe((result) => {
      row = result;
      this.ngOnInit();
    });
    console.log('Row clicked: ', row);
  }

  onOpenRejectDialogAr(row): void {
    const dialogRef = this.dialog.open(RejectArrivalComponent, {
      width: '60%',
      // height: "850",
      data: { row },
    });
    dialogRef.afterClosed().subscribe((result) => {
      row = result;
      this.ngOnInit();
    });
    console.log('Row clicked: ', row);
  }


  approve(row): void {
    const dialogRef = this.dialog.open(ApproveReservationComponent, {
      width: '60%',
      // height: "850",
      data: { row },
    });
    dialogRef.afterClosed().subscribe((result) => {
      row = result;
      this.ngOnInit();
    });
    console.log('Row clicked: ', row);
  }

  approveAr(row): void {
    const dialogRef = this.dialog.open(ApproveArrivalReservationComponent, {
      width: '60%',
      // height: "850",
      data: { row },
    });
    dialogRef.afterClosed().subscribe((result) => {
      row = result;
      this.ngOnInit();
    });
    console.log('Row clicked: ', row);
  }

  onOpenArCancelDialog(row): void {
    const dialogRef = this.dialog.open(CancelArrivalReservationComponent, {
      width: '60%',
      // height: "850",
      data: { row },
    });
    dialogRef.afterClosed().subscribe((result) => {
      row = result;
      this.ngOnInit();
    });
    console.log('Row clicked: ', row);
  }

  makeCancellationRequest(row): void {
    const dialogRef = this.dialog.open(CancellationRequestComponent, {
      width: '60%',
      // height: "850",
      data: { row },
    });
    dialogRef.afterClosed().subscribe((result) => {
      row = result;
      this.ngOnInit();
    });
  }

  confirmCancellationRequest(row): void {
    const dialogRef = this.dialog.open(ConfirmCancellationComponent, {
      width: '60%',
      // height: "850",
      data: { row },
    });
    dialogRef.afterClosed().subscribe((result) => {
      row = result;
      this.ngOnInit();
    });
  }

  cancellationRequest(row): void {
    const dialogRef = this.dialog.open(CancelReservationComponent, {
      width: '60%',
      // height: "850",
      data: { row },
    });
    dialogRef.afterClosed().subscribe((result) => {
      row = result;
      this.ngOnInit();
    });
  }
}
