import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterLink, RouterOutlet } from '@angular/router'; 
import { AuthService } from '../../services/auth';

@Component({
  selector: 'app-main-layout',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterOutlet], 
  templateUrl: './main-layout.html',
  styleUrl: './main-layout.css'
})
export class MainLayout {

  userRole: 'patient' | 'doctor' | string |null = null;
  
  constructor(private router: Router, private authService : AuthService) {
    this.userRole = this.authService.getRole();
  }

  getDashboardRoute(): string{
    return this.userRole  === 'doctor' ? '/doctor-dashboard' : '/dashboard';
  }

  logout() {
    console.log('Korisnik se odjavljuje iz layout-a...');
    localStorage.removeItem('token');
    this.router.navigate(['/login']);
  }
}