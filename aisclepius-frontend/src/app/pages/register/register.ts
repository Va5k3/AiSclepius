import { Component } from '@angular/core';
import { AuthService } from '../../services/auth';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common'; 
import { FormsModule } from '@angular/forms';     

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './register.html'
})
export class RegisterComponent {
  registerData = {
    name: '',
    email: '',
    password: '',
    role: 'patient' // Po defaultu se postavlja na pacijent
  };
  errorMessage = '';

  constructor(private authService: AuthService, private router: Router) {}

  onRegister() {
    this.errorMessage = '';
    this.authService.register(this.registerData).subscribe({
      next: (res) => {
        if (res.success) {
          if (res.role === 'doctor') {
            this.router.navigate(['/doctor-dashboard']);
          } else {
            this.router.navigate(['/diabetes-check']);
          }
        }
      },
      error: (err) => {
        this.errorMessage = err.error?.message || 'Greška prilikom registracije.';
      }
    });
  }
}