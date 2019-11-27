import { Component, OnInit, ViewChild } from '@angular/core';
import {
  MatTableDataSource,
  MatPaginator,
  MatDialog,
  MatDialogConfig,
  MatSort
} from '@angular/material';

@Component({
  selector: 'app-view-my-slots',
  templateUrl: './view-my-slots.component.html',
  styleUrls: ['./view-my-slots.component.scss']
})
export class ViewMySlotsComponent implements OnInit {
  displayedColumns: string[] = [
    'time',
    'slot',
    'bus_number',
    'status',
    'date',
    'action'
  ];

  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;

  dataSource = new MatTableDataSource<PeriodicElement>(ELEMENT_DATA);
  constructor() {}

  ngOnInit() {
    this.dataSource.paginator = this.paginator;
    this.dataSource.sort = this.sort;
  }

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  cancel() {}
}

export interface PeriodicElement {
  slot: string;
  time: string;
  bus_number: string;
  status: string;
  date: Date;
}

const ELEMENT_DATA: PeriodicElement[] = [
  {
    time: '05:00',
    status: 'Approved',
    slot: 'slot_one',
    bus_number: 'ABC1234',
    date: new Date(2019, 1, 23)
  },
  {
    time: '05:00',
    status: 'Pending Approved',
    slot: 'slot_one',
    bus_number: 'ABC1535',
    date: new Date(2019, 1, 23)
  },
  {
    time: '05:00',
    status: 'Approved',
    slot: 'slot_one',
    bus_number: 'ABC1203',
    date: new Date(2019, 1, 23)
  },
  {
    time: '05:00',
    status: 'Pending Approved',
    slot: 'slot_one',
    bus_number: 'ABC1234',
    date: new Date(2019, 1, 23)
  },
  {
    time: '05:00',
    status: 'Rejected',
    slot: 'slot_one',
    bus_number: 'ABC1234',
    date: new Date(2019, 1, 23)
  },
  {
    time: '12:00',
    status: 'Approved',
    slot: 'slot_one',
    bus_number: 'ABC1234',
    date: new Date(2019, 1, 23)
  },
  {
    time: '13:00',
    status: 'Rejected',
    slot: 'slot_one',
    bus_number: 'ABC1234',
    date: new Date(2019, 1, 23)
  },
  {
    time: '14:00',
    status: 'Approved',
    slot: 'slot_one',
    bus_number: 'ABC1234',
    date: new Date(2019, 1, 23)
  }
];

