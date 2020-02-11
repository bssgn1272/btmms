import { Routes } from '@angular/router';


import { DashboardComponent } from '../../dashboard/dashboard.component';
import { UserProfileComponent } from '../../user-profile/user-profile.component';
import { DispatchComponent } from '../../components/dispatch-details/dispatch-details.component';
import { MakeBookingComponent } from 'app/make-booking/make-booking.component';
import { ViewMySlotsComponent } from 'app/view-my-slots/view-my-slots.component';
import { Role } from 'app/models/role';
import { RoservationRequestsComponent } from 'app/roservation-requests/roservation-requests.component';
import { AuthGuard } from 'app/guard/auth.guard';
import { SettingsComponent } from '../../settings/settings.component';
import { DestinationDayComponent } from '../../destination-day/destination-day.component';
import { SlotTimeComponent } from '../../slot-time/slot-time.component';
import { UpdateSlotTimeComponent } from '../../update-slot-time/update-slot-time.component';
import { RejectComponent } from '../../reject/reject.component';
import { CancelReservationComponent } from '../../cancel-reservation/cancel-reservation.component';

export const AdminLayoutRoutes: Routes = [
         { path: 'user-profile', component: DashboardComponent },
         {
           path: 'dashboard',
           component: UserProfileComponent,
           canActivate: [AuthGuard]
         },
         {
           path: 'make-booking',
           component: MakeBookingComponent,
           canActivate: [AuthGuard]
         },
         {
           path: 'veiw-slot',
           component: ViewMySlotsComponent,
           canActivate: [AuthGuard]
           //  data: { roles: [Role.admin] }
         },
         {
           path: 'veiw-resavations-requests',
           component: RoservationRequestsComponent,
           canActivate: [AuthGuard]
           //  data: { roles: [Role.admin] }
         },
         {
           path: 'settings',
           component: SettingsComponent,
           canActivate: [AuthGuard]
           //  data: { roles: [Role.admin] }
         },
         {
           path: 'destination-time',
           component: DestinationDayComponent,
           canActivate: [AuthGuard]
         },
         {
           path: 'slot-time',
           component: SlotTimeComponent,
           canActivate: [AuthGuard]
         },
         {
           path: 'update-slot',
           component: UpdateSlotTimeComponent,
           canActivate: [AuthGuard]
         },
         {
           path: 'reject',
           component: RejectComponent,
           canActivate: [AuthGuard]
         },
         {
           path: 'cancel',
           component: CancelReservationComponent,
           canActivate: [AuthGuard]
         }
       ];
