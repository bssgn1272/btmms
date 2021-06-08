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
import { OpenSlotsService } from './vehicle-grid.service';
import {DpEditReservationComponent} from '../dp-edit-reservation/dp-edit-reservation.component';
import {ArEditResevertionComponent} from '../ar-edit-resevertion/ar-edit-resevertion.component';
import {MatSnackBar, MatSnackBarHorizontalPosition, MatSnackBarVerticalPosition} from '@angular/material/snack-bar';
import {SettingsService} from '../settings/settings.service';
import {ViewMyPenaltiesService} from '../view-my-penalties/view-my-penalties.service';
import {OptionsService} from '../options/options.service';


@Component({
  selector: 'app-vehicle-grid',
  templateUrl: './vehicle-grid.component.html',
  styleUrls: ['./vehicle-grid.component.css']
})
export class VehicleGridComponent implements OnInit {
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
    'slot_nine'
  ];
  dataSource = new MatTableDataSource([]);
  arDataSource = new MatTableDataSource([]);
  valDate: Date = new Date();
  valArDate: Date = new Date();
  minDate: Date = new Date();
  maxDate: Date = new Date();

  horizontalPosition: MatSnackBarHorizontalPosition = 'start';
  verticalPosition: MatSnackBarVerticalPosition = 'top';

  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;
  @ViewChild(MatPaginator) arPaginator: MatPaginator;
  @ViewChild(MatSort) arSort: MatSort;
  userItems: any;
  user: any;
  operatingDate: any;
  arOperatingDate: any;
  selectedSlot: any
  penalties: any[] = [];
  penalty: any[] = [];
  allow_booking_with_penalty: any;
  constructor(
    private dialog: MatDialog,
    private el: ElementRef,
    private slots: OpenSlotsService,
    private _snackBar: MatSnackBar,
    private penaltiesService: ViewMyPenaltiesService,
    private optionsService: OptionsService
  ) {
    this.valDate.setDate(this.valDate.getDate() + 1);
    this.valArDate.setDate(this.valDate.getDate() + 1);
  }

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem('currentUser'));
    return users;
  }

  ngOnInit() {
    this.refresh();

  }

  refresh() {
    this.userItems = this.getFromLocalStrorage();
    this.user = this.userItems.username;
    this.minDate = new Date();
    this.maxDate = new Date();
    this.minDate.setDate(this.minDate.getDate() + 1);
    this.maxDate.setDate(this.maxDate.getDate() + 7);

    this.operatingDate = this.convertDate(this.valDate);
    this.arOperatingDate = this.convertDate(this.valArDate);
    sessionStorage.setItem('operatingDate', this.operatingDate);

    this.slots.getList(this.operatingDate).then((res) => {
      console.log('Slots', res)
      this.dataSource = new MatTableDataSource(res.data);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    });

    this.slots.arGetList(this.arOperatingDate).then(res => {
      this.arDataSource = new MatTableDataSource(res.data);
      this.arDataSource.paginator = this.arPaginator;
      this.arDataSource.sort = this.arSort;

      console.log('Arrival Routes', res)
    });

    this.optionsService.getOption(9).subscribe((res) => {
      this.allow_booking_with_penalty = res.data[0].option_value;
    })

    this.penaltiesService.getList(this.userItems.ID).subscribe((res) => {
      this.penalties = res.data;

      for (let i = 0; i < this.penalties.length; i++) {
        this.penalty = [...this.penalty, ...this.penalties[i].penalty_status]
      }

      console.log('PENALTIES>>>>>>', this.penalty)
    })

    this.arOperatingDate = this.convertDate(this.valDate);
    sessionStorage.setItem('arOperatingDate', this.arOperatingDate);
  }

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  arApplyFilter(filterValue: string) {
    this.arDataSource.filter = filterValue.trim().toLowerCase();
  }

  public onChange(event): void {
    this.valDate = event.value;
    const dt = this.convertDate(event.value);
    console.log('DATE>>>', dt);

    // this.slots.testSlots().subscribe((res: any) => {
    this.slots.getList(dt).then(res => {
      this.dataSource = new MatTableDataSource(res.data);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    });

    this.operatingDate = dt;
    sessionStorage.setItem('operatingDate', this.operatingDate);

  }

  public onArChange(event): void {
    this.valArDate = event.value;
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
    if (this.userItems.account_status === 'NON_COMPLIANT') {
      this._snackBar.open('You can not perform this action due to non-compliance.  Please call Napsa for assistance', 'Close', {
        duration: 25000,
        verticalPosition: 'top'
      });
    } else if (this.penalty.includes('Unpaid') && this.allow_booking_with_penalty === '0') {
      this._snackBar.open('You can not perform this action due to penalty.  Please call Napsa for assistance', 'Close', {
        duration: 25000,
        verticalPosition: 'top'
      });
    } else {
      const dialogRef = this.dialog.open(MakeBookingComponent, {
        width: '60%',
        data: {row}
      });
      dialogRef.afterClosed().subscribe(result => {
        this.refresh();
      });
    }
  }

  onOpenEditDialog(user, slot, time, reserved_date): void {
    if (this.userItems.account_status === 'NON_COMPLIANT') {
      this._snackBar.open('You can not perform this action due to non-compliance.  Please call Napsa for assistance', 'Close', {
        duration: 2500,
        verticalPosition: 'top'
      });
    } else if (this.penalty.includes('Unpaid') && this.allow_booking_with_penalty === '0') {
      this._snackBar.open('You can not perform this action due to penalty.  Please call Napsa for assistance', 'Close', {
        duration: 2500,
        verticalPosition: 'top'
      });
    } else {
      let row = {user: user, slot: slot, time: time, reserved_date: reserved_date}
      if (this.user === user) {
        sessionStorage.setItem('mode', 'DEP');
        const dialogRef = this.dialog.open(DpEditReservationComponent, {
          width: '60%',
          data: {row}
        });
        dialogRef.afterClosed().subscribe(result => {
          this.refresh();
        });
      }
    }

  }

  arOnOpenDialog(row): void {
    sessionStorage.setItem('mode', 'ARR');
    if (this.userItems.account_status === 'NON_COMPLIANT') {
      this._snackBar.open('You can not perform this action due to non-compliance.  Please call Napsa for assistance', 'Close', {
        duration: 2500,
        verticalPosition: 'top'
      });
    } else if (this.penalty.includes('Unpaid') && this.allow_booking_with_penalty === '0') {
      this._snackBar.open('You can not perform this action due to penalty.  Please call Napsa for assistance', 'Close', {
        duration: 2500,
        verticalPosition: 'top'
      });
    } else {
      const dialogRef = this.dialog.open(ArMakeBookingComponent, {
        width: '60%',
        data: {row}
      });
      dialogRef.afterClosed().subscribe(result => {
        this.refresh();
      });
    }
  }


  onOpenEditDialogAR(user, slot, time, reserved_date): void {
    if (this.userItems.account_status === 'NON_COMPLIANT') {
      this._snackBar.open('You can not perform this action due to non-compliance.  Please call Napsa for assistance', 'Close', {
        duration: 2500,
        verticalPosition: 'top'
      });
    } else if (this.penalty.includes('Unpaid') && this.allow_booking_with_penalty === '0') {
      this._snackBar.open('You can not perform this action due to penalty.  Please call Napsa for assistance', 'Close', {
        duration: 2500,
        verticalPosition: 'top'
      });
    } else {
      let row = {user: user, slot: slot, time: time, reserved_date: reserved_date}
      if (this.user === user) {
        sessionStorage.setItem('mode', 'DEP');
        const dialogRef = this.dialog.open(ArEditResevertionComponent, {
          width: '60%',
          data: {row}
        });
        dialogRef.afterClosed().subscribe(result => {
          this.refresh();
        });
      }
    }

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
