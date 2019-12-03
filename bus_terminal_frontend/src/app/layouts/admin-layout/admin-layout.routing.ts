import { Routes } from '@angular/router';
// import { AuthGuard } from 'app/guard/auth.guard';
import { AuthGuard } from '../../_guards/auth.guard';

import { DashboardComponent } from '../../dashboard/dashboard.component';
import { UserProfileComponent } from '../../user-profile/user-profile.component';
import { DispatchComponent } from '../../components/dispatch-details/dispatch-details.component';
import { TypographyComponent } from '../../typography/typography.component';
import { MakeBookingComponent } from 'app/make-booking/make-booking.component';
import { ViewMySlotsComponent } from 'app/view-my-slots/view-my-slots.component';
import { Role } from 'app/models/role';
import { RoservationRequestsComponent } from 'app/roservation-requests/roservation-requests.component';

export const AdminLayoutRoutes: Routes = [
  { path: 'user-profile', component: DashboardComponent },
  {
    path: 'dashboard',
    component: UserProfileComponent
    //  canActivate: [AuthGuard]
  },
  { path: 'reports', component: TypographyComponent },
  {
    path: 'make-booking',
    component: MakeBookingComponent
    //  canActivate: [AuthGuard]
  },
  {
    path: 'veiw-slot',
    component: ViewMySlotsComponent
    //  canActivate: [AuthGuard]
  },
  {
    path: 'veiw-resavations-requests',
    component: RoservationRequestsComponent
    //  canActivate: [AuthGuard]
  }
];
