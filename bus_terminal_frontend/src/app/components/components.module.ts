import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';

import {
  MatButtonModule,
  MatInputModule,
  MatRippleModule,
  MatFormFieldModule,
  MatTooltipModule,
  MatSelectModule,
  MatTableModule,
  MatPaginatorModule,
  MatDialogModule,
  MatDatepickerModule,
  MatNativeDateModule,
  MatSortModule,
  MatSnackBarModule,
  MatMenuModule,
  MatStepperModule,
  MatTabsModule,
  MatIconModule,
  MAT_DIALOG_DATA,
  MatDialogRef,
  MatSlideToggleModule,
} from "@angular/material";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { SignaturePadModule } from "angular2-signaturepad";
import { DateRangePickerModule } from "@syncfusion/ej2-angular-calendars";
import { HttpClientModule } from "@angular/common/http";

import { FooterComponent } from './footer/footer.component';
import { NavbarComponent } from './navbar/navbar.component';
import { SidebarComponent } from './sidebar/sidebar.component';
import { DispatchComponent } from './dispatch-details/dispatch-details.component';
import { ChangePasswordComponent } from 'app/change-password/change-password.component';

@NgModule({
  imports: [
    CommonModule,
    RouterModule,
    FormsModule,
    MatButtonModule,
    MatRippleModule,
    MatFormFieldModule,
    MatInputModule,
    MatSelectModule,
    MatTooltipModule,
    MatTableModule,
    MatPaginatorModule,
    MatDialogModule,
    ReactiveFormsModule,
    MatDatepickerModule,
    MatNativeDateModule,
    MatSnackBarModule,
    MatMenuModule,
    SignaturePadModule,
    MatSortModule,
    MatStepperModule,
    HttpClientModule,
    MatTabsModule,
    MatIconModule,
    DateRangePickerModule,
    MatSlideToggleModule,
  ],
  declarations: [
    FooterComponent,
    NavbarComponent,
    SidebarComponent,
    ChangePasswordComponent
  ],
  exports: [
    FooterComponent,
    NavbarComponent,
    SidebarComponent,
    ChangePasswordComponent
  ],
  entryComponents: [
    ChangePasswordComponent,
  ]
})
export class ComponentsModule { }
