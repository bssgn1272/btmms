import { Component, OnInit, ViewChild } from '@angular/core';
import {
  MatTableDataSource,
  MatPaginator,
  MatDialog,
  MatDialogConfig
} from '@angular/material';
import { MakeBookingComponent } from 'app/make-booking/make-booking.component';


@Component({
  selector: 'app-user-profile',
  templateUrl: './user-profile.component.html',
  styleUrls: ['./user-profile.component.css']
})
export class UserProfileComponent implements OnInit {
  displayedColumns: string[] = [
    'time',
    'status',
    'status2',
    'status3',
    'status4',
    'status5',
    'action'
  ];

  @ViewChild(MatPaginator) paginator: MatPaginator;

  dataSource = new MatTableDataSource<PeriodicElement>(ELEMENT_DATA);

  constructor(private dialog: MatDialog) {}

  ngOnInit() {
    this.dataSource.paginator = this.paginator;
  }

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  // group details
  onOpenDialog() {
    const dialogConfig = new MatDialogConfig();
    // dialogConfig.disableClose = true;
    dialogConfig.autoFocus = true;
    dialogConfig.width = '60%';
    this.dialog.open(MakeBookingComponent, dialogConfig);
  }
}

export interface PeriodicElement {
  status: string;
  time: string;
  status2: string;
  status3: string;
  status4: string;
  status5: string;
}

const ELEMENT_DATA: PeriodicElement[] = [
  {
    time: '05:00',
    status: 'Open',
    status2: 'Open',
    status3: 'Open',
    status4: 'Open',
    status5: 'Open'
  },
  {
    time: '06:00',
    status: 'Open',
    status2: 'Open',
    status3: 'Open',
    status4: 'Open',
    status5: 'Open'
  },
  {
    time: '07:00',
    status: 'Open',
    status2: 'Open',
    status3: 'Open',
    status4: 'Open',
    status5: 'Open'
  },
  {
    time: '08:00',
    status: 'Open',
    status2: 'Open',
    status3: 'Open',
    status4: 'Open',
    status5: 'Open'
  },
  {
    time: '09:00',
    status: 'Open',
    status2: 'Open',
    status3: 'Open',
    status4: 'Open',
    status5: 'Open'
  },
  {
    time: '10:00',
    status: 'Open',
    status2: 'Open',
    status3: 'Open',
    status4: 'Open',
    status5: 'Open'
  },
  {
    time: '11:00',
    status: 'Open',
    status2: 'Open',
    status3: 'Open',
    status4: 'Open',
    status5: 'Open'
  },
  {
    time: '12:00',
    status: 'Open',
    status2: 'Open',
    status3: 'Open',
    status4: 'Open',
    status5: 'Open'
  },
  {
    time: '13:00',
    status: 'Open',
    status2: 'Open',
    status3: 'Open',
    status4: 'Open',
    status5: 'Open'
  },
  {
    time: '14:00',
    status: 'Open',
    status2: 'Open',
    status3: 'Open',
    status4: 'Open',
    status5: 'Open'
  },
  {
    time: '15:00',
    status: 'Open',
    status2: 'Open',
    status3: 'Open',
    status4: 'Open',
    status5: 'Open'
  },
  {
    time: '16:00',
    status: 'Open',
    status2: 'Open',
    status3: 'Open',
    status4: 'Open',
    status5: 'Open'
  }
];
