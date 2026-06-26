import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-doctor-dashboard',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './doctor-dashboard.page.html',
  styleUrls: ['./doctor-dashboard.page.css'] // promeni u .scss ako koristiš sass
})
export class DoctorDashboardComponent {
  // Ovde kasnije dodajemo pozive ka Laravelu za povlačenje pacijenata iz baze
}