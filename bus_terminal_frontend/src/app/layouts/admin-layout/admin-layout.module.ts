import { NgModule } from "@angular/core";
import { RouterModule } from "@angular/router";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { HttpClientModule } from "@angular/common/http";
import { AdminLayoutRoutes } from "./admin-layout.routing";
import { DashboardComponent } from "../../dashboard/dashboard.component";
import { UserProfileComponent } from "../../user-profile/user-profile.component";
import { SignaturePadModule } from "angular2-signaturepad";
import { DateRangePickerModule } from "@syncfusion/ej2-angular-calendars";
import { SatDatepickerModule, SatNativeDateModule } from "saturn-datepicker";

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
import { MakeBookingComponent } from "app/make-booking/make-booking.component";
import { ArMakeBookingComponent } from "app/ar-make-booking/ar-make-booking.component";
import { ViewMySlotsComponent } from "app/view-my-slots/view-my-slots.component";
import { RoservationRequestsComponent } from "app/roservation-requests/roservation-requests.component";
import { AlertsComponent } from "app/alert/alerts.component";
import { AlertsService } from "app/alert/alerts.service";
import { NavbarComponent } from "app/components/navbar/navbar.component";
import { SettingsComponent } from "app/settings/settings.component";
import { DestinationDayComponent } from "app/destination-day/destination-day.component";
import { SlotTimeComponent } from "app/slot-time/slot-time.component";
import { UpdateSlotTimeComponent } from "../../update-slot-time/update-slot-time.component";
import { RejectComponent } from "../../reject/reject.component";
import { CancelReservationComponent } from "../../cancel-reservation/cancel-reservation.component";
import { CancellationRequestComponent } from "../../cancellation-request/cancellation-request.component";
import { ConfirmCancellationComponent } from "../../confirm-cancellation/confirm-cancellation.component";
import { ModesComponent } from "../../settings/components/modes/modes.component";
import { DueTimeComponent } from "../../settings/components/due-time/due-time.component";

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
    MatStepperModule,
    HttpClientModule,
    MatTabsModule,
    MatIconModule,
    DateRangePickerModule,
    MatSlideToggleModule,
  ],
  declarations: [
    DashboardComponent,
    UserProfileComponent,
    MakeBookingComponent,
    ArMakeBookingComponent,
    ViewMySlotsComponent,
    RoservationRequestsComponent,
    AlertsComponent,
    SettingsComponent,
    DestinationDayComponent,
    SlotTimeComponent,
    UpdateSlotTimeComponent,
    RejectComponent,
    CancelReservationComponent,
    CancellationRequestComponent,
    ConfirmCancellationComponent,
    ModesComponent,
    DueTimeComponent,
  ],
  providers: [
    AlertsService,
    { provide: MAT_DIALOG_DATA, useValue: {} },
    { provide: MatDialogRef, useValue: {} },
  ],
})
export class AdminLayoutModule {}
