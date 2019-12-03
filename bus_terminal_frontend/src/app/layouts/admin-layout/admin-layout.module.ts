import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { AdminLayoutRoutes } from './admin-layout.routing';
import { DashboardComponent } from '../../dashboard/dashboard.component';
import { UserProfileComponent } from '../../user-profile/user-profile.component';
import { TypographyComponent } from '../../typography/typography.component';
import { SignaturePadModule } from 'angular2-signaturepad';

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
  MatMenuModule
} from '@angular/material';
import { MakeBookingComponent } from 'app/make-booking/make-booking.component';
import { ViewMySlotsComponent } from 'app/view-my-slots/view-my-slots.component';
import { RoservationRequestsComponent } from 'app/roservation-requests/roservation-requests.component';
import { ApproveReservationComponent } from 'app/approve-reservation/approve-reservation.component';
import { AlertsComponent } from 'app/alert/alerts.component';
import { AlertsService } from 'app/alert/alerts.service';
@NgModule({
  imports: [
    CommonModule,
    RouterModule.forChild(AdminLayoutRoutes),
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
    HttpClientModule
  ],
  declarations: [
    DashboardComponent,
    UserProfileComponent,
    TypographyComponent,
    MakeBookingComponent,
    ViewMySlotsComponent,
    RoservationRequestsComponent,
    ApproveReservationComponent,
    AlertsComponent
  ],
  providers: [
    AlertsService
  ]
})
export class AdminLayoutModule {}
