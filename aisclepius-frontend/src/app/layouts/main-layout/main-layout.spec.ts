import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
// 1. Uvozimo SVE potrebne alate za ruter
import { Router, RouterLink, RouterOutlet } from '@angular/router'; 

@Component({
  selector: 'app-main-layout',
  standalone: true,
  // 2. Dodajemo RouterLink i RouterOutlet u imports
  imports: [CommonModule, RouterLink, RouterOutlet], 
  templateUrl: './main-layout.component.html',
  styleUrl: './main-layout.component.css'
})
export class MainLayoutComponent {
  
  // 3. Dodajemo constructor da dobijemo Router "GPS"
  constructor(private router: Router) {}

  // 4. Dodajemo logout metodu koju HTML poziva
  logout() {
    console.log('Korisnik se odjavljuje iz layout-a...');
    
    // Brišemo token iz localStorage-a
    localStorage.removeItem('token');
    
    // Vraćamo korisnika na login formu
    this.router.navigate(['/login']);
  }
}