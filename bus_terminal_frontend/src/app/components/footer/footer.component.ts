import { Component, OnInit } from '@angular/core';
import { ChangePasswordComponent } from 'app/change-password/change-password.component';
import { MatDialog } from '@angular/material';

@Component({
  selector: 'app-footer',
  templateUrl: './footer.component.html',
  styleUrls: ['./footer.component.css']
})
export class FooterComponent implements OnInit {
  test : Date = new Date();
  activated = false;
  dialogRef: any;
  currentUser: any;
  
  constructor(
    private dialog: MatDialog,
  ) { }

  ngOnInit() {
    this.currentUser = this.getFromLocalStorage();

    if (this.currentUser.account_status === 'ACTIVE') {
      this.activated = true;
    }

    if(this.currentUser.account_status === 'OTP'){
      //this.changePasswordDialog();
    }
  }

  changePasswordDialog(): void {
    console.log(this.currentUser);
    this.dialogRef = this.dialog.open(ChangePasswordComponent, {
      width: '60%',
      data: {activated: this.activated}
    });
    
    if(!this.activated){
      this.dialogRef.disableClose = true;
    }
    this.dialogRef.afterClosed().subscribe(result => {
      this.currentUser = this.getFromLocalStorage();
      if (this.currentUser.account_status === 'ACTIVE') {
        this.activated = true;
      }
    });
  }

  public getFromLocalStorage() {
    const users = JSON.parse(localStorage.getItem('currentUser'));
    return users;
  }

}
