import { Component, OnInit } from '@angular/core';
import { MatDialogRef } from '@angular/material';

export interface Slot {
  value: string;
  viewValue: string;
}

@Component({
  selector: 'app-make-booking',
  templateUrl: './make-booking.component.html',
  styleUrls: ['./make-booking.component.scss']
})
export class MakeBookingComponent implements OnInit {
  slot: Slot[] = [
    { value: 'slot-0', viewValue: 'Slot One' },
    { value: 'slot-1', viewValue: 'Slot Two' },
    { value: 'slot-2', viewValue: 'Slot Three' },
    { value: 'slot-3', viewValue: 'Slot Four' },
    { value: 'slot-4', viewValue: 'Slot Five' }
  ];
  constructor(public dialogRef: MatDialogRef<MakeBookingComponent>) {}

  ngOnInit() {}

  save() {}

  close() {
    this.dialogRef.close();
  }
}
