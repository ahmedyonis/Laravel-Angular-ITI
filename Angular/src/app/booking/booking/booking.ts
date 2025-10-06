import { Component, inject, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import {
  BookingService,
  ShowDetails,
  Seat,
  FoodItem,
  BookingRequest
} from '../../services/booking-service';
import { AuthService } from '../../services/auth';

@Component({
  selector: 'app-booking',
  standalone: true,
  templateUrl: './booking.html',
  styleUrls: ['./booking.css']
})
export class BookingComponent implements OnInit {
  bookingService = inject(BookingService);
  authService = inject(AuthService);
  router = inject(Router);
  route = inject(ActivatedRoute);

  showId!: number;
  showDetails: ShowDetails | null = null;
  seats: Seat[] = [];
  foodItems: FoodItem[] = [];
  selectedSeatIds: number[] = [];
  selectedFood: { [foodId: number]: number } = {};

  ngOnInit(): void {
    if (!this.authService.isLoggedIn()) {
      this.router.navigate(['/login']);
      return;
    }

    this.showId = Number(this.route.snapshot.paramMap.get('id'));
    this.loadShowAndSeats();
    this.loadFoodItems();
  }

  loadShowAndSeats(): void {
    this.bookingService.getShowWithSeats(this.showId).subscribe(response => {
      this.showDetails = response.show;
      this.seats = response.seats;
    });
  }

  loadFoodItems(): void {
    this.bookingService.getFoodItems().subscribe(food => {
      this.foodItems = food;
      this.foodItems.forEach(item => {
        this.selectedFood[item.id] = 0;
      });
    });
  }

  toggleSeat(seat: Seat): void {
    if (seat.is_booked) return;
    const index = this.selectedSeatIds.indexOf(seat.id);
    if (index === -1) {
      this.selectedSeatIds.push(seat.id);
    } else {
      this.selectedSeatIds.splice(index, 1);
    }
  }

  isSeatSelected(seatId: number): boolean {
    return this.selectedSeatIds.includes(seatId);
  }

  updateFoodQuantity(foodId: number, change: number): void {
    const newQty = this.selectedFood[foodId] + change;
    if (newQty >= 0) {
      this.selectedFood[foodId] = newQty;
    }
  }

  calculateTotal(): number {
    let total = 0;

    // سعر المقاعد
    this.selectedSeatIds.forEach(seatId => {
      const seat = this.seats.find(s => s.id === seatId);
      if (seat && this.showDetails) {
        if (seat.seat_class === 'first') {
          total += parseFloat(this.showDetails.price_first_class);
        } else if (seat.seat_class === 'second') {
          total += parseFloat(this.showDetails.price_second_class);
        } else {
          total += parseFloat(this.showDetails.price_standard);
        }
      }
    });

    // سعر الأكل
    Object.keys(this.selectedFood).forEach(foodIdStr => {
      const foodId = Number(foodIdStr);
      const qty = this.selectedFood[foodId];
      if (qty > 0) {
        const food = this.foodItems.find(f => f.id === foodId);
        if (food) {
          total += parseFloat(food.price) * qty;
        }
      }
    });

    return total;
  }

  getUniqueRows(): string[] {
  const rows = this.seats.map(seat => seat.seat_number.charAt(0));
  return [...new Set(rows)].sort();
}

  getSeatsByRow(row: string): Seat[] {
  return this.seats.filter(seat => seat.seat_number.startsWith(row));
}

  getSeatClassForRow(row: string): string {
  const firstSeat = this.seats.find(seat => seat.seat_number.startsWith(row));
  return firstSeat ? firstSeat.seat_class : 'standard';
}

  submitBooking(): void {
    if (this.selectedSeatIds.length === 0) {
      alert('Please select at least one seat.');
      return;
    }

    const food_items = Object.keys(this.selectedFood)
      .map(id => ({
        id: Number(id),
        quantity: this.selectedFood[Number(id)]
      }))
      .filter(item => item.quantity > 0);

    const bookingData: BookingRequest = {
      show_id: this.showId,
      seat_ids: this.selectedSeatIds,
      food_items: food_items.length > 0 ? food_items : undefined
    };

    this.bookingService.createBooking(bookingData).subscribe({
      next: () => {
        alert('Booking confirmed!');
        this.router.navigate(['/shows']);
      },
      error: () => {
        alert('Booking failed. Please try again.');
      }
    });
  }
}