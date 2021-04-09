import {Component, ElementRef, OnInit, ViewChild} from '@angular/core';
import {MatSnackBar, MatSnackBarHorizontalPosition, MatSnackBarVerticalPosition} from '@angular/material/snack-bar';
import {MatTableDataSource} from '@angular/material/table';
import {MatPaginator} from '@angular/material/paginator';
import {MatSort} from '@angular/material/sort';
import {OpenSlotsService} from '../user-profile/user-profile.service';
import {MatDialog} from '@angular/material/dialog';
import {ArMakeBookingComponent} from '../ar-make-booking/ar-make-booking.component';
import {ArEditResevertionComponent} from '../ar-edit-resevertion/ar-edit-resevertion.component';
import {MakeFlexiBookingComponent} from '../make-flexi-booking/make-flexi-booking.component';

@Component({
  selector: 'app-flexi-booking',
  templateUrl: './flexi-booking.component.html',
  styleUrls: ['./flexi-booking.component.scss']
})
export class FlexiBookingComponent implements OnInit {
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

  arDataSource = new MatTableDataSource([]);
  minDate: Date = new Date();
  maxDate: Date = new Date();

  horizontalPosition: MatSnackBarHorizontalPosition = 'start';
  verticalPosition: MatSnackBarVerticalPosition = 'top';


  @ViewChild(MatPaginator) arPaginator: MatPaginator;
  @ViewChild(MatSort) arSort: MatSort;
  userItems: any;
  user: any;

  arOperatingDate: any;

  constructor(
      private dialog: MatDialog,
      private el: ElementRef,
      private slots: OpenSlotsService,
      private _snackBar: MatSnackBar,
  ) { }


  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem('currentUser'));
    return users;
  }

  ngOnInit() {
    this.refresh();
  }

  refresh() {
    console.log("TTTTTATTATATTATATATATTATATATATAAAAAAAAAAATAAAAAAAAAAAAAATTTTTTTTTTTTTTTTTTTTTTTT")
    this.userItems = this.getFromLocalStrorage();
    this.user = this.userItems.username;
    this.minDate.setDate(this.minDate.getDate() + 1);
    this.maxDate.setDate(this.maxDate.getDate() + 7);

    this.slots.arGetList().then(res => {
      console.log('DATATATTATTAATATATATAT', res)
      this.arDataSource = new MatTableDataSource(res.data);
      this.arDataSource.paginator = this.arPaginator;
      this.arDataSource.sort = this.arSort;

      console.log('ARRival ROUTES', res)
    });

    this.arOperatingDate = this.convertDate(this.minDate);
    sessionStorage.setItem('arOperatingDate', this.arOperatingDate);
  }

  arApplyFilter(filterValue: string) {
    this.arDataSource.filter = filterValue.trim().toLowerCase();
  }


  public onArChange(event): void {
    const dt = this.convertDate(event.value);

    this.slots.arGetList(dt).then(res => {
      this.arDataSource = new MatTableDataSource(res.data);
      this.arDataSource.paginator = this.arPaginator;
      this.arDataSource.sort = this.arSort;
    });

    this.arOperatingDate = dt;
    sessionStorage.setItem('arOperatingDate', this.arOperatingDate);
    console.log(dt);
  }



  arOnOpenDialog(row): void {
    sessionStorage.setItem('mode', 'ARR');
    if (this.userItems.account_status === 'NON_COMPLIANT') {
      this._snackBar.open('You are can not perform this action due to non-compliance.  Please call Napsa for assistance', 'Close', {
        duration: 2500,
        verticalPosition: 'top'
      });
    } else {
      const dialogRef = this.dialog.open(MakeFlexiBookingComponent, {
        width: '60%',
        data: {row}
      });
      dialogRef.afterClosed().subscribe(() => {
        this.refresh();
      });
    }
  }


  // onOpenEditDialogAR(user, slot, time, reserved_date): void {
  //   if (this.userItems.account_status === 'NON_COMPLIANT') {
  //     this._snackBar.open('You are can not perform this action due to non-compliance.  Please call Napsa for assistance', 'Close', {
  //       duration: 2500,
  //       verticalPosition: 'top'
  //     });
  //   } else {
  //     let row = {user: user, slot: slot, time: time, reserved_date: reserved_date}
  //     if (this.user === user) {
  //       sessionStorage.setItem('mode', 'DEP');
  //       const dialogRef = this.dialog.open(ArEditResevertionComponent, {
  //         width: '60%',
  //         data: {row}
  //       });
  //       dialogRef.afterClosed().subscribe(result => {
  //         row = result;
  //       });
  //     }
  //   }
  //
  // }


  convertDate(d: Date): String {
    const dd = d.getDate();
    const mm = d.getMonth() + 1;
    const yyyy = d.getFullYear();
    let day = '' + dd;
    let month = '' + mm;

    if (dd < 10) {
      day = '0' + dd;
    }

    if (mm < 10) {
      month = '0' + mm;
    }
    const dt = yyyy + '-' + month + '-' + day + ' ' + '20:00:00';
    return dt;
  }

}
