import {Component, OnInit, ViewChild} from '@angular/core';
import {MatTableDataSource} from '@angular/material/table';
import {MatSort} from '@angular/material/sort';
import {MatPaginator} from '@angular/material/paginator';
import {ActivatedRoute, Router} from '@angular/router';
import {MatSnackBar} from '@angular/material/snack-bar';
import {formatDate} from '@angular/common';
import {ViewMyChargesService} from './view-my-charges.service';

@Component({
  selector: 'app-view-my-charges',
  templateUrl: './view-my-charges.component.html',
  styleUrls: ['./view-my-charges.component.scss']
})
export class ViewMyChargesComponent implements OnInit {
  from: any;
  to: any;
  selectedFilter = '';

  displayedColumns: string[] = [
    'charge_desc',
    'charge_amount',
    'charge_freq',
  ];

  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;

  dataSource = new MatTableDataSource([]);

  displayData: any;

  filterDataSource: any;
  userItems: any;

  constructor(
      private route: ActivatedRoute,
      private router: Router,
      private _snackBar: MatSnackBar,
      private ViewMyChargesService: ViewMyChargesService
  ) { }

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem('currentUser'));
    return users;
  }

  ngOnInit() {
    this.userItems = this.getFromLocalStrorage();
    const _id = this.userItems.ID;

    this.ViewMyChargesService.getList().subscribe((res) => {
      console.log('MY SLOTS>>>>', _id, res.data, 'ss');
      this.displayData = res.data;
      this.filterDataSource = this.displayData;
      this.dataSource = new MatTableDataSource(this.displayData);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    });
  }
}
