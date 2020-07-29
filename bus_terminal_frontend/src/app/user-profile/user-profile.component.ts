import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import {
  MatTableDataSource,
  MatPaginator,
  MatDialog,
  MatDialogConfig,
  MatSort
} from '@angular/material';
import { MakeBookingComponent } from 'app/make-booking/make-booking.component';
import { ArMakeBookingComponent } from 'app/ar-make-booking/ar-make-booking.component';
import { OpenSlotsService } from './user-profile.service';


@Component({
  selector: 'app-user-profile',
  templateUrl: './user-profile.component.html',
  styleUrls: ['./user-profile.component.css']
})
export class UserProfileComponent implements OnInit {
  displayedColumns: string[] = [
    'time',
    'slot_one',
    'slot_two',
    'slot_three',
    'slot_four',
    'slot_five',
    'slot_six',
    'slot_seven',
    'slot_eight',
    'slot_nine',
    'action'
  ];
  dataSource = new MatTableDataSource([]);
  arDataSource = new MatTableDataSource([]);
  minDate: Date = new Date();
  maxDate: Date = new Date();

  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;
  @ViewChild(MatPaginator) arPaginator: MatPaginator;
  @ViewChild(MatSort) arSort: MatSort;
  userItems: any;
  user: any;
  operatingDate: any;
  arOperatingDate: any;
  constructor(
    private dialog: MatDialog,
    private el: ElementRef,
    private slots: OpenSlotsService
  ) {}

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem('currentUser'));
    return users;
  }

  ngOnInit() {

    this.userItems = this.getFromLocalStrorage();
    this.user = this.userItems.username;
    this.minDate.setDate(this.minDate.getDate() + 1);
    this.maxDate.setDate(this.maxDate.getDate() + 7);

    this.slots.getList().then(res => {
      this.dataSource = new MatTableDataSource(res.data);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    });

    this.slots.arGetList().then(res => {
      this.arDataSource = new MatTableDataSource(res.data);
      this.arDataSource.paginator = this.arPaginator;
      this.arDataSource.sort = this.arSort;
    });

    this.operatingDate = this.convertDate(this.minDate);
    sessionStorage.setItem('operatingDate', this.operatingDate);

    this.arOperatingDate = this.convertDate(this.minDate);
    sessionStorage.setItem('arOperatingDate', this.arOperatingDate);
  }

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  arApplyFilter(filterValue: string) {
    this.arDataSource.filter = filterValue.trim().toLowerCase();
  }

  public onChange(event): void {
    var dt = this.convertDate(event.value);

    this.slots.getList(dt).then(res => {
      this.dataSource = new MatTableDataSource(res.data);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    });

    this.operatingDate = dt;
    sessionStorage.setItem('operatingDate', this.operatingDate);
    console.log(dt);
  }

  public onArChange(event): void {
    var dt = this.convertDate(event.value);

    this.slots.arGetList(dt).then(res => {
      this.arDataSource = new MatTableDataSource(res.data);
      this.arDataSource.paginator = this.paginator;
      this.arDataSource.sort = this.sort;
    });

    this.arOperatingDate = dt;
    sessionStorage.setItem('arOperatingDate', this.arOperatingDate);
    console.log(dt);
  }

  // group details
  onOpenDialog(row): void {
    sessionStorage.setItem('mode', 'DEP');
    const dialogRef = this.dialog.open(MakeBookingComponent, {
      width: '60%',
      data: { row }
    });
    dialogRef.afterClosed().subscribe(result => {
      row = result;
    });
  }

  arOnOpenDialog(row): void {
    sessionStorage.setItem('mode', 'ARR');
    const dialogRef = this.dialog.open(ArMakeBookingComponent, {
      width: '60%',
      data: { row }
    });
    dialogRef.afterClosed().subscribe(result => {
      row = result;
    });
  }

  convertDate(d: Date): String {
    var dd = d.getDate();
    var mm = d.getMonth() + 1; 
    var yyyy = d.getFullYear();
    var day = '' + dd;
    var month = '' + mm;

    if(dd < 10){
        day = '0' + dd;
    } 
    
    if(mm < 10){
        month = '0' + mm;
    }
    var dt = yyyy + '-' + month + '-' + day + ' ' + '20:00:00';
    return dt;
  }
}
