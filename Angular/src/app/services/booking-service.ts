import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface ShowDetails {
  total_seats: number;
  id: number;
  title: string;
  show_date: string;
  show_time: string;
  price_first_class: string;
  price_second_class: string;
  price_standard: string;
  image: string;
}

export interface Seat {
  id: number;
  seat_number: string;
  seat_class: 'first' | 'second' | 'standard';
  is_booked: boolean;
}

export interface FoodItem {
  id: number;
  name: string;
  price: string;
}

export interface BookingRequest {
  show_id: number;
  seat_ids: number[];
  food_items?: { id: number; quantity: number }[];
}

export interface Booking {
  id: number;
  show: { id: number; title: string; show_date: string; show_time: string };
  seats: { id: number; seat_number: string; seat_class: string }[];
  foodItems: { id: number; name: string; price: string; quantity: number }[];
  total_amount: string;
  status: string;
  created_at: string;
}

@Injectable({
  providedIn: 'root'
})
export class BookingService {
  private apiUrl = 'http://127.0.0.1:8000/api';

  constructor(private http: HttpClient) {}

  getShowWithSeats(showId: number): Observable<{ show: ShowDetails; seats: Seat[] }> {
    return this.http.get<{ show: ShowDetails; seats: Seat[] }>(`${this.apiUrl}/shows/${showId}`);
  }

  getFoodItems(): Observable<FoodItem[]> {
    return this.http.get<FoodItem[]>(`${this.apiUrl}/food`);
  }

  createBooking( data : BookingRequest): Observable<any> {
    return this.http.post(`${this.apiUrl}/bookings`, data);
  }

  getMyBookings(): Observable<Booking[]> {
  return this.http.get<Booking[]>(`${this.apiUrl}/bookings`);
  }

  cancelBooking(bookingId: number): Observable<any> {
  return this.http.delete(`${this.apiUrl}/bookings/${bookingId}`);
  }
}