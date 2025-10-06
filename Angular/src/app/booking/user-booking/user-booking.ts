import { Component, inject, OnInit  } from '@angular/core';
import { RouterLink } from '@angular/router';
import { Booking, BookingService } from '../../services/booking-service';
import { AuthService } from '../../services/auth';

@Component({
  selector: 'app-user-booking',
  standalone: true,
  imports: [RouterLink],
  templateUrl: './user-booking.html',
  styleUrl: './user-booking.css'
})
export class UserBooking implements OnInit {
  bookingService = inject(BookingService);
  authService = inject(AuthService);

  bookings: Booking[] = [];
  loading = true;

  ngOnInit(): void {
    if (!this.authService.isLoggedIn()) {
      return;
    }
    this.loadBookings();
  }

  loadBookings(): void {
  this.bookingService.getMyBookings().subscribe({
    next: (bookings: any) => { 
      console.log('Bookings ', bookings);
      this.bookings = bookings;
      this.loading = false;
    },
    error: (err) => {
      console.error('Full error:', err); 
      this.loading = false;
      alert('Failed to load bookings. Check console for details.');
    }
  });
}

  cancelBooking(id: number): void {
    if (confirm('Are you sure you want to cancel this booking?')) {
      this.bookingService.cancelBooking(id).subscribe({
        next: () => {
          // حدّث القائمة بعد الإلغاء
          this.bookings = this.bookings.map(b => 
            b.id === id ? { ...b, status: 'cancelled' } : b
          );
          alert('Booking cancelled successfully.');
        },
        error: () => {
          alert('Failed to cancel booking.');
        }
      });
    }
  }
}
