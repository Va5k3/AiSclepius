import { Component } from '@angular/core';
import { AuthService } from '../../services/auth';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common'; 
import { FormsModule } from '@angular/forms';     


@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './login.html',
  styleUrls: ['./login.css'] 
})
export class LoginComponent {
  // Model koji se vezuje za HTML input polja
  credentials = {
    email: '',
    password: ''
  };
  errorMessage = '';

  constructor(private authService: AuthService, private router: Router) {}

  onLogin() {
    this.errorMessage = '';
    
    this.authService.login(this.credentials).subscribe({
      next: (res) => {
        if (res.success) {
          // Preusmeravanje na osnovu uloge dobijene sa Backenda
          if (res.role === 'doctor') {
            this.router.navigate(['/doctor-dashboard']);
          } else {
            this.router.navigate(['/dashboard']); // Tvoja podrazumevana ruta za pacijente
          }
        }
      },
      error: (err) => {
        this.errorMessage = err.error?.message || 'Pogrešan email ili lozinka.';
      }
    });
  }
}