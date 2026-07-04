import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterLink } from '@angular/router';
import { HttpClient, HttpHeaders } from '@angular/common/http'; // Uvezi HttpClient
import { environment } from '../../../environments/environment';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule, RouterLink], 
  templateUrl: './dashboard.html',
  styleUrl: './dashboard.css'
})
export class Dashboard implements OnInit {
  appointments: any[] = []; // Ovde će se čuvati pregledi sa bekenda
  isLoggedIn: boolean = false;
  private apiUrl = `${environment.apiBaseUrl}/appointments`;
  constructor(private http: HttpClient, private router: Router) {}

  ngOnInit() {
    this.isLoggedIn = !!localStorage.getItem('auth_token');
    this.loadAppointments();
  }

  loadAppointments() {
    const token = localStorage.getItem('auth_token');
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });

    this.http.get<any[]>(this.apiUrl, { headers }).subscribe({
      next: (res) => {
        this.appointments = res;
      },
      error: (err) => {
        console.error('Greška pri dobijanju pregleda:', err);
      }
    });
  }
}