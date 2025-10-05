// src/app/auth/login/login.component.ts
import { Component, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [FormsModule],
  templateUrl: './login.html',
  styleUrls: ['./login.css']
})
export class LoginComponent {
  authService = inject(AuthService);
  router = inject(Router);

  login(formValue: any): void {
    const { email, password } = formValue;
    this.authService.login(email, password).subscribe({
      next: () => {
        this.router.navigate(['/shows']);
      },
      error: () => {
        alert('Invalid email or password');
      }
    });
  }
}