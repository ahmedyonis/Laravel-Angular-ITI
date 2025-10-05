// src/app/auth/register/register.component.ts
import { Component, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth';

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [FormsModule],
  templateUrl: './register.html',
  styleUrls: ['./register.css']
})
export class RegisterComponent {
  authService = inject(AuthService);
  router = inject(Router);

  register(formValue: any): void {
    const { name, email, password, password_confirmation } = formValue;
    this.authService.register({
      name,
      email,
      password,
      password_confirmation
    }).subscribe({
      next: () => {
        // alert('Registration successful! Please login.');
        this.router.navigate(['/login']);
      },
      error: () => {
        alert('Registration failed. Please try again.');
      }
    });
  }
}
