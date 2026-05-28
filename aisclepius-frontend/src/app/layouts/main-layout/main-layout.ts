import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterLink, RouterOutlet } from '@angular/router'; 

@Component({
  selector: 'app-main-layout',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterOutlet], 
  templateUrl: './main-layout.html',
  styleUrl: './main-layout.css'
})
export class MainLayout {
  
  constructor(private router: Router) {}

  logout() {
    console.log('Korisnik se odjavljuje iz layout-a...');
    localStorage.removeItem('token');
    this.router.navigate(['/login']);
  }
}