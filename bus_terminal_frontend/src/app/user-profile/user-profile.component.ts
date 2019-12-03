import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import {
  MatTableDataSource,
  MatPaginator,
  MatDialog,
  MatDialogConfig,
  MatSort
} from '@angular/material';
import { MakeBookingComponent } from 'app/make-booking/make-booking.component';
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
    'action'
  ];
  dataSource = new MatTableDataSource([]);

  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;
  constructor(
    private dialog: MatDialog,
    private el: ElementRef,
    private slots: OpenSlotsService
  ) {}

  ngOnInit() {
    this.slots.getList().then(res => {
      this.dataSource = new MatTableDataSource(res.data);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    });
  }

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  // group details
  onOpenDialog(row): void {
    const dialogRef = this.dialog.open(MakeBookingComponent, {
      width: '60%',
      // height: "850",
      data: { row }
    });
    dialogRef.afterClosed().subscribe(result => {
      row = result;
    });
    console.log('Row clicked: ', row);
    // const dialogConfig = new MatDialogConfig();
    // dialogConfig.disableClose = true;
    // dialogConfig.autoFocus = true;
    // dialogConfig.width = '60%';
    // this.dialog.open(MakeBookingComponent, dialogConfig);
  }
}
