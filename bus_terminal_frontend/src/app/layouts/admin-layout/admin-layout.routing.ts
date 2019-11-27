import { Routes } from '@angular/router';

import { DashboardComponent } from '../../dashboard/dashboard.component';
import { UserProfileComponent } from '../../user-profile/user-profile.component';
import { DispatchComponent } from '../../components/dispatch-details/dispatch-details.component';
import { TypographyComponent } from '../../typography/typography.component';
import { MakeBookingComponent } from 'app/make-booking/make-booking.component';
import { ViewMySlotsComponent } from 'app/view-my-slots/view-my-slots.component';

export const AdminLayoutRoutes: Routes = [
  { path: 'user-profile', component: DashboardComponent },
  { path: 'dashboard', component: UserProfileComponent },
  { path: 'reports', component: TypographyComponent },
  {
    path: 'make-booking',
    component: MakeBookingComponent
  },
  {
    path: 'veiw-slot',
    component: ViewMySlotsComponent
  }
];
