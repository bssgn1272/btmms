import {Component, OnInit, ViewChild} from '@angular/core';
import { MatDialog } from '@angular/material';
import {MatTableDataSource} from '@angular/material/table';
import {MatSort} from '@angular/material/sort';
import {MatPaginator} from '@angular/material/paginator';
import {ActivatedRoute, Router} from '@angular/router';
import {MatSnackBar} from '@angular/material/snack-bar';
import {formatDate} from '@angular/common';
import {OptionsService} from './options.service';
import {ChangeOptionComponent} from '../change-option/change-option.component';

@Component({
  selector: 'app-options',
  templateUrl: './options.component.html',
  styleUrls: ['./options.component.scss']
})
export class OptionsComponent implements OnInit {
  from: any;
  to: any;
  selectedFilter = '';

  displayedColumns: string[] = [
    'optionName',
    'optionValue',
    'actions'
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
      private OptionsService: OptionsService,
      private dialog: MatDialog
  ) { }

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem('currentUser'));
    return users;
  }

  ngOnInit() {
    this.userItems = this.getFromLocalStrorage();

    this.OptionsService.getOptions().subscribe((res) => {
      console.log('Options>>>>', res.data);
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

  onChangeOption(row: any): void {
    const dialogRef = this.dialog.open(ChangeOptionComponent, {
      width: "60%",
      // height: "850",
      data: { row },
    });
    dialogRef.afterClosed().subscribe((result) => {
      row = result;
    });
    console.log("Row clicked: ", row);
  }
}
