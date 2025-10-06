import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { ShowDetails } from './booking-service'; 

export interface AdminBooking {
  id: number;
  user: { name: string; email: string };
  show: { title: string; show_date: string; show_time: string };
  seats: { seat_number: string }[];
  total_amount: string;
  status: string;
}

@Injectable({
  providedIn: 'root'
})
export class AdminService {
  private apiUrl = 'http://127.0.0.1:8000/api';

  constructor(private http: HttpClient) {}

  // العروض
  getShows(): Observable<ShowDetails[]> {
    return this.http.get<ShowDetails[]>(`${this.apiUrl}/admin/shows`);
  }

  createShow(formData: FormData): Observable<ShowDetails> {
    return this.http.post<ShowDetails>(`${this.apiUrl}/admin/shows`, formData);
  }

  updateShow(id: number, formData: FormData): Observable<ShowDetails> {
    return this.http.post<ShowDetails>(`${this.apiUrl}/admin/shows/${id}`, formData);
  }

  deleteShow(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/admin/shows/${id}`);
  }

  // الحجوزات
  getBookings(): Observable<AdminBooking[]> {
    return this.http.get<AdminBooking[]>(`${this.apiUrl}/admin/bookings`);
  }

  deleteBooking(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/admin/bookings/${id}`);
  }

  
}

  


export type { ShowDetails };
