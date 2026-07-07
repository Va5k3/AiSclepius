import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
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
export class MainLayout implements OnInit{

  userRole: 'patient' | 'doctor' | string |null = null;
  userName: string | null = null;
  isLoggedIn: boolean = false;
  
  constructor(private router: Router, private authService : AuthService, private cdr : ChangeDetectorRef) {
    this.userRole = this.authService.getRole();
  }

  ngOnInit(){
    this.isLoggedIn = !!localStorage.getItem('auth_token');
    if(this.isLoggedIn){
      this.userName = localStorage.getItem('name');
      let role = this.authService.getRole();
      if(role=='patient')
        this.userRole = 'PACIJENT';
      else
        this.userRole = 'DOKTOR';
    }
  }

  getDashboardRoute(): string{
      const token = localStorage.getItem('auth_token');
      const role = this.authService.getRole();

  if (!token) {
    return '/dashboard';
  }

  return role === 'doctor' ? '/doctor-dashboard' : '/dashboard';
  }

  logout() {
    console.log('Korisnik se odjavljuje iz layout-a...');
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user_role');
    localStorage.removeItem('name'); 
    localStorage.removeItem('user_data');
    this.isLoggedIn = false;
    this.userName = null;
    this.router.navigate(['/login']);
  }
}