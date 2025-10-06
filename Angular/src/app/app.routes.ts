import { Routes } from '@angular/router';
import { LoginComponent } from './auth/login/login';
import { RegisterComponent } from './auth/register/register';
import { ShowsComponent } from './show/shows/shows';
import { BookingComponent } from './booking/booking/booking';
import { UserBooking } from './booking/user-booking/user-booking';
import { AdminComponent } from './show/admin-shows/admin-shows';

export const routes: Routes = [
  { path: 'login', component: LoginComponent },
  { path: 'register', component: RegisterComponent },
  { path: 'shows', component: ShowsComponent },
  { path: 'booking/:id', component: BookingComponent },
  { path: 'my-bookings', component:  UserBooking},
  { path: 'admin', component: AdminComponent },
  { path: '', redirectTo: '/shows', pathMatch: 'full' }, 
  { path: '**', redirectTo: '/shows' }
];