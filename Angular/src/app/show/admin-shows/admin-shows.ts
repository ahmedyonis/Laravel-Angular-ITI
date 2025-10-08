import { Component, inject, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AdminService, AdminBooking, ShowDetails } from '../../services/admin-service';
import { AuthService } from '../../services/auth';

@Component({
  selector: 'app-admin',
  standalone: true,
  imports: [FormsModule],
  templateUrl: './admin-shows.html'
})
export class AdminComponent implements OnInit {
  adminService = inject(AdminService);
  authService = inject(AuthService);
  router = inject(Router);

 activeTab: 'shows' | 'bookings' = 'shows';

  // --- العروض ---
  shows: ShowDetails[] = [];
  newShow = {
    title: '',
    price_first_class: '',
    price_second_class: '',
    price_standard: ''
  };
  imageFile: File | null = null;
  showFormVisible = false;

  editMode = false;
  currentShowId: number | null = null;

  // --- الحجوزات ---
  bookings: any[] = [];

  ngOnInit(): void {
    if (!this.authService.isAdmin()) {
      this.router.navigate(['/shows']);
      return;
    }
    this.loadShows();
    this.loadBookings();
  }

  // --- التبويبات ---
  switchTab(tab: 'shows' | 'bookings'): void {
    this.activeTab = tab;
  }

  submitShowForm(formValue: any): void {
  if (this.editMode && this.currentShowId !== null) {
    this.updateShow(this.currentShowId, formValue);
  } else {
    this.createShow(formValue);
  }
  }

  // --- إدارة العروض ---
  loadShows(): void {
    this.adminService.getShows().subscribe({
      next: (shows) => {
        this.shows = shows;
      },
      error: () => {
        alert('Failed to load shows.');
      }
    });
  }

  toggleShowForm(): void {
    this.showFormVisible = !this.showFormVisible;
    if (!this.showFormVisible) {
      this.resetForm();
    }
  }

  resetForm(): void {
    this.newShow = {
      title: '',
      price_first_class: '',
      price_second_class: '',
      price_standard: ''
    };
    this.imageFile = null;
    this.editMode = false;
    this.currentShowId = null;
  }

  onFileChange(event: any): void {
    this.imageFile = event.target.files[0];
  }

  createShow(formValue: any): void {
    const formData = new FormData();
    formData.append('title', formValue.title);
    formData.append('price_first_class', formValue.price_first_class);
    formData.append('price_second_class', formValue.price_second_class);
    formData.append('price_standard', formValue.price_standard);

    if (this.imageFile) {
      formData.append('image', this.imageFile);
    }

    this.adminService.createShow(formData).subscribe({
      next: () => {
        this.loadShows(); // تحديث القائمة
        this.showFormVisible = false;
        this.resetForm();
      },
      error: () => {
        alert('Failed to create show.');
      }
    });
  }

  deleteShow(id: number): void {
    if (confirm('Delete this show?')) {
      this.adminService.deleteShow(id).subscribe({
        next: () => {
          this.shows = this.shows.filter(show => show.id !== id);
        },
        error: () => {
          alert('Failed to delete show.');
        }
      });
    }
  }

  // --- إدارة الحجوزات ---
  loadBookings(): void {
    this.adminService.getBookings().subscribe({
      next: (bookings) => {
        this.bookings = bookings;
      },
      error: () => {
        alert('Failed to load bookings.');
      }
    });
  }

  deleteBooking(id: number): void {
    if (confirm('Delete this booking permanently?')) {
      this.adminService.deleteBooking(id).subscribe({
        next: () => {
          this.bookings = this.bookings.filter(b => b.id !== id);
        },
        error: () => {
          alert('Failed to delete booking.');
        }
      });
    }
  }
  editShow(show: ShowDetails): void {
    console.log('Editing show:', show);
    this.editMode = true;
    this.currentShowId = show.id;
    this.newShow = {
      title: show.title || '',
      price_first_class: show.price_first_class?.toString() || '',
      price_second_class: show.price_second_class?.toString() || '',
      price_standard: show.price_standard?.toString() || ''
    };
    this.imageFile = null;
    this.showFormVisible = true;

}

updateShow(id: number, formValue: any): void {
  const formData = new FormData();
  formData.append('_method', 'PUT');
  formData.append('title', formValue.title);
  formData.append('price_first_class', formValue.price_first_class);
  formData.append('price_second_class', formValue.price_second_class);
  formData.append('price_standard', formValue.price_standard);

  if (this.imageFile) {
    formData.append('image', this.imageFile);
  }

  this.adminService.updateShow(id, formData).subscribe({
    next: () => {
      this.loadShows();
      this.showFormVisible = false;
      this.resetForm();
      this.editMode = false;
      this.currentShowId = null;
    },
    error: () => {
      alert('Failed to update show.');
    }
  });
}
}