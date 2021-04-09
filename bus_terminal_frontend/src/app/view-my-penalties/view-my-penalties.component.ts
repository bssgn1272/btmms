import {Component, OnInit, ViewChild} from '@angular/core';
import {MatTableDataSource} from '@angular/material/table';
import {MatSort} from '@angular/material/sort';
import {MatPaginator} from '@angular/material/paginator';
import {ActivatedRoute, Router} from '@angular/router';
import {MatSnackBar} from '@angular/material/snack-bar';
import {formatDate} from '@angular/common';
import {ViewMyPenaltiesService} from './view-my-penalties.service';

@Component({
  selector: 'app-view-my-penalties',
  templateUrl: './view-my-penalties.component.html',
  styleUrls: ['./view-my-penalties.component.scss']
})
export class ViewMyPenaltiesComponent implements OnInit {
  from: any;
  to: any;
  selectedFilter = '';

  displayedColumns: string[] = [
    'username',
    'type',
    'date_booked',
    'date_paid',
    'status',
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
      private viewMyPenaltiesService: ViewMyPenaltiesService
  ) { }

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem('currentUser'));
    return users;
  }

  ngOnInit() {
    this.userItems = this.getFromLocalStrorage();
    const _id = this.userItems.ID;

    this.viewMyPenaltiesService.getList(_id).subscribe((res) => {
      console.log('MY SLOTS>>>>', _id, res.data, 'ss');
      this.displayData = res.data;
      this.filterDataSource = this.displayData;
      this.dataSource = new MatTableDataSource(this.displayData);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    });
  }


  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }


  dateRange() {

      this.filterDataSource = this.displayData.filter(
          (x) =>
              x.date_booked >
              formatDate(this.from, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530') &&
              x.date_booked <
              formatDate(this.to, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530')
      );

    this.dataSource = new MatTableDataSource(this.filterDataSource);
    this.dataSource.paginator = this.paginator;
    this.dataSource.sort = this.sort;
    console.log(
        this.displayData,
        formatDate(this.from, 'yyy-MM-dd hh:mm:ss', 'en-US', '+0530')
    );
  }


}
