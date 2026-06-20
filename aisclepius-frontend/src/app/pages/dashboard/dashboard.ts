import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterLink } from '@angular/router';
import { HttpClient } from '@angular/common/http'; // Uvezi HttpClient

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule, RouterLink], 
  templateUrl: './dashboard.html',
  styleUrl: './dashboard.css'
})
export class Dashboard implements OnInit {
  appointments: any[] = []; // Ovde će se čuvati pregledi sa bekenda

  constructor(private http: HttpClient, private router: Router) {}

  ngOnInit() {
    this.loadAppointments();
  }

  loadAppointments() {
    this.http.get<any[]>('http://localhost:8001/api/appointments').subscribe({
      next: (res) => {
        this.appointments = res;
      },
      error: (err) => {
        console.error('Greška pri dobijanju pregleda:', err);
      }
    });
  }
}