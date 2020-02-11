import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { MatTableDataSource, MatPaginator, MatSort, MatSnackBar } from '@angular/material';
import { ReservationRequestsService } from './reservation-requests.service';
import { HttpClient } from '@angular/common/http';
import { Location, DatePipe } from '@angular/common';
import { ActivatedRoute, Router } from '@angular/router';
import { FormGroup, FormBuilder, FormControl } from '@angular/forms';
import { OpenSlotsService } from './slot.service';

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

  filterForm = new FormGroup({
    fromDate: new FormControl(),
    toDate: new FormControl()
  });
  pipe: DatePipe;

  get fromDate() {
    return this.filterForm.get('fromDate').value;
  }
  get toDate() {
    return this.filterForm.get('toDate').value;
  }
  // Roservation Requests
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

  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;
  slot_status: any;

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
    private httpClient: HttpClient,
    private router: Router,
    private _snackBar: MatSnackBar,
    public _location: Location,
    private slots: OpenSlotsService
  ) {
    this.pipe = new DatePipe('en');
    this.dataSource.filterPredicate = (data, filter) => {
      if (this.fromDate && this.toDate) {
        return (
          data.reserved_time >= this.fromDate &&
          data.reserved_time <= this.toDate
        );
      }
      return true;
    };
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

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  applySlotFilter(filterValue: string) {
    this.dataSourceSlot.filter = filterValue.trim().toLowerCase();
  }

  applyDateFilter() {
    this.dataSource.filter = '' + Math.random();
  }

  approve(row) {
    this.slot = row.slot;
    this.id = row.ID;
    this.status = 'A';
    this.time = row.time;
    this.user = row.username;
    console.log(this.slot);

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
        () => {
          window.location.reload()
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        () => {
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
    this.slot = row.slot;
    this.slot_status = row.username;
    this.time = row.time;
    console.log(this.slot);
    this.id = row.id;
    this.status = 'R';

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
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        () => {
          this._location.back();
          // this.router
          //   .navigateByUrl('/veiw-resavations-requests', {
          //     skipLocationChange: true
          //   })
          //   .then(() => {
          //     this.router.navigate([decodeURI(this._location.path())]);
          //   });
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        () => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );

    console.log(this.slot);

    // this.router.navigateByUrl('/veiw-resavations-requests', { skipLocationChange: true }).then(() => {
    //   this.router.navigate([decodeURI(this._location.path())]);
    // })
    console.log(row);
  }
}
