import {Component, Inject, OnInit, Optional, ViewChild} from '@angular/core';
import {
  MatTableDataSource,
  MatPaginator,
  MatDialog,
  MatDialogConfig,
  MatSort,
  MatSnackBar,
} from '@angular/material';
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute, Router } from '@angular/router';
import { ViewSlotsService } from './view-slots.service';
import { AuthService } from 'app/login/auth.service';
import { Location, formatDate } from '@angular/common';
import { CancelReservationComponent } from '../cancel-reservation/cancel-reservation.component';
import * as moment from 'moment';
import {CancelArrivalReservationComponent} from '../cancel-arrival-reservation/cancel-arrival-reservation.component';
import {animate, state, style, transition, trigger} from '@angular/animations';
import {ArMakeBookingComponent} from '../ar-make-booking/ar-make-booking.component';
import {VeiwSubDestinationsComponent} from '../veiw-sub-destinations/veiw-sub-destinations.component';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import { ChangeBusComponent } from "../change-bus/change-bus.component";



@Component({
  selector: 'app-view-slots',
  templateUrl: './view-slots.component.html',
  styleUrls: ['./view-slots.component.scss'],
  animations: [
    trigger('detailExpand', [
      state('collapsed', style({height: '0px', minHeight: '0'})),
      state('expanded', style({height: '*'})),
      transition('expanded <=> collapsed', animate('225ms cubic-bezier(0.4, 0.0, 0.2, 1)')),
    ]),
  ],
})
export class ViewSlotsComponent implements OnInit {

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
  time = '';
  from: any;
  to: any;
  fromHistory: any;
  toHistory: any;
  selectedFilter = '';
  selectedHistoryFilter = '';
  displayedColumns: string[] = [
    'time',
    'slot',
    'license_plate',
    'route',
    'status',
    'reserved_time',
    'action',
  ];




  dataSource = new MatTableDataSource([]);
  arDataSource = new MatTableDataSource([]);

  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;

  @ViewChild(MatPaginator) arPaginator: MatPaginator;
  @ViewChild(MatSort) arSort: MatSort;
  userItems: any;


  displayedHistoryColumns: string[] = [
    'time',
    'slot',
    'license_plate',
    'route',
    'status',
    'reserved_time',
  ];

  dataSourceHistory = new MatTableDataSource([]);

  @ViewChild('HistoryPaginator') paginatorHistory: MatPaginator;
  @ViewChild('HistorySort') sortHistory: MatSort;
  displayData: any;
  arDisplayData: any;
  filterDataSource: any;
  arFilterDataSource: any;
  rStatus: string;
  displayDataHistory: any;
  filterDataSourceHistory: any;



  constructor(
    private httpClient: HttpClient,
    private route: ActivatedRoute,
    private router: Router,
    private _snackBar: MatSnackBar,
    private viewSlots: ViewSlotsService,
    private authenticationService: AuthService,
    private _location: Location,
    private dialog: MatDialog,
  ) {}

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem('currentUser'));
    return users;
  }

  ngOnInit() {
    this.userItems = this.getFromLocalStrorage();
    const _id = this.userItems.ID;

    this.viewSlots.arGetListAll().then((res) => {
      console.log('MY SLOTS>>>>', _id, res.data);
      this.displayData = res.data;
      this.filterDataSource = this.displayData;
      this.dataSource = new MatTableDataSource(this.displayData);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    });

    /* this.viewSlots.arGetListAll().then((res) => {
      console.log('MY AR SLOTS>>>>', res.data);
      this.arDisplayData = res.data;
      this.filterDataSource = this.arDisplayData;
      this.arDataSource = new MatTableDataSource(this.arDisplayData);
      this.arDataSource.paginator = this.arPaginator;
      this.arDataSource.sort = this.arSort;
    }); */

    this.viewSlots.getHistoryList().then((res) => {
      this.displayDataHistory = res.data;
      this.filterDataSourceHistory = this.displayDataHistory;
      this.dataSourceHistory = new MatTableDataSource(res.data);
      this.dataSourceHistory.paginator = this.paginatorHistory;
      this.dataSourceHistory.sort = this.sortHistory;
    });

    // console.log(this.currentUser.id);
  }

  isExpansionDetailRow = (i: number, row: Object) => row.hasOwnProperty('detailRow');

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  arApplyFilter(filterValue: string) {
    this.arDataSource.filter = filterValue.trim().toLowerCase();
  }

  applyHistoryFilter(filterValue: string) {
    this.dataSourceHistory.filter = filterValue.trim().toLowerCase();
  }

  // add Open Dialog

  onOpenCancelDialog(row): void {
    const dialogRef = this.dialog.open(CancelArrivalReservationComponent, {
      width: '60%',
      // height: "850",
      data: { row },
    });
    dialogRef.afterClosed().subscribe((result) => {
      row = result;
    });
    console.log('Row clicked: ', row);
  }

  onChangeVehicleDialog(row): void {
    const dialogRef = this.dialog.open(ChangeBusComponent, {
      width: "60%",
      // height: "850",
      data: { row },
    });
    dialogRef.afterClosed().subscribe((result) => {
      row = result;
    });
    console.log("Row clicked: ", row);
  }

  onOpenCancelArrivalDialog(row): void {
    const dialogRef = this.dialog.open(CancelArrivalReservationComponent, {
      width: '60%',
      // height: "850",
      data: { row },
    });
    dialogRef.afterClosed().subscribe((result) => {
      row = result;
    });
    console.log('Row clicked: ', row);
  }

  log(value: any) {
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
      } else if (value === 'DL') {
        this.rStatus = 'DL';
      } else if (value === 'D') {
        this.rStatus = 'D';
      } else if (value === 'R') {
        this.rStatus = 'R';
      }
    }
    // });
  }

  arLog(value: any) {
    // this.requests.getList().then((res) => {
    if (value === 'All') {
      this.arFilterDataSource = this.arDisplayData;
      this.arDataSource = new MatTableDataSource(this.arFilterDataSource);
      this.arDataSource.paginator = this.arPaginator;
      this.arDataSource.sort = this.arSort;
    } else {
      this.filterDataSource = this.arDisplayData.filter(
        (x) => x.status === value
      );
      this.arDataSource = new MatTableDataSource(this.arFilterDataSource);
      this.arDataSource.paginator = this.arPaginator;
      this.arDataSource.sort = this.arSort;
      if (value === 'All') {
        this.rStatus = 'All';
      } else if (value === 'C') {
        this.rStatus = 'C';
      } else if (value === 'A') {
        this.rStatus = 'A';
      } else if (value === 'PC') {
        this.rStatus = 'PC';
      } else if (value === 'DL') {
        this.rStatus = 'DL';
      } else if (value === 'D') {
        this.rStatus = 'D';
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

  arDateRange() {
    console.log(this.from, this.to);
    if (this.rStatus !== 'All') {
      this.arFilterDataSource = this.arDisplayData.filter(
        (x) =>
          x.reserved_time >
            formatDate(this.from, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530') &&
          x.reserved_time <
            formatDate(this.to, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530') &&
          x.status === this.rStatus
      );
    } else {
      this.arFilterDataSource = this.arDisplayData.filter(
        (x) =>
          x.reserved_time >
            formatDate(this.from, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530') &&
          x.reserved_time <
            formatDate(this.to, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530')
      );
    }

    this.arDataSource = new MatTableDataSource(this.arFilterDataSource);
    this.arDataSource.paginator = this.arPaginator;
    this.arDataSource.sort = this.arSort;
    console.log(
      this.arDisplayData,
      formatDate(this.from, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530')
    );
  }

  logHistory(value: any) {
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

  dateRangeHistory() {
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
            formatDate(this.toHistory, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530')
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
      this.displayData,
      formatDate(this.fromHistory, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530')
    );
  }


  getSubRoutes(row: any) {
    const dialogRef = this.dialog.open(VeiwSubDestinationsComponent, {
      width: '60%',
      data: { row }
    });
    dialogRef.afterClosed().subscribe(result => {
      row = result;
    });
  }
}
