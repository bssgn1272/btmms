import { Component, OnInit } from '@angular/core';
import { MatDialogRef } from '@angular/material';


@Component({
  selector: 'app-dispatch-details',
  templateUrl: 'dispatch-details-dialog.html',
})
export class DispatchComponent implements OnInit {

  constructor(public dialogRef: MatDialogRef<DispatchComponent>) { }

  ngOnInit(): void {
  }

  onClose() {
    this.dialogRef.close();
  }

}

