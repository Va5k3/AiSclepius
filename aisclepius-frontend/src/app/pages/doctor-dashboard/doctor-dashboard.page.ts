import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { AuthService } from '../../services/auth';
import { environment } from '../../../environments/environment';

@Component({
  selector: 'app-doctor-dashboard',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './doctor-dashboard.page.html'
})
export class DoctorDashboardComponent implements OnInit {
  patients: any[] = [];
  selectedPatient: any = null;
  isLoading: boolean = true;

  // Podaci za medicinski karton selektovanog pacijenta
  notes: string = '';
  medications: string[] = [];
  newMedication: string = ''; // Pomoćna promenljiva za input polje

  private baseUrl = environment.apiBaseUrl;

  constructor(private authService: AuthService, private http: HttpClient, private cdr: ChangeDetectorRef) {}

  ngOnInit(): void {
    this.loadPatients();
  }

  // Get headers sa tokenom iz tvog AuthService-a
  private getHeaders() {
    const token = localStorage.getItem('auth_token');
    return new HttpHeaders({ 'Authorization': `Bearer ${token}` });
  }

  loadPatients(): void {
    this.http.get<any>(`${this.baseUrl}/patients`, { headers: this.getHeaders() }).subscribe({
      next: (res) => {
        if (res.success && res.data){ 
          this.patients = res.data;
          this.cdr.detectChanges();
        }
        this.isLoading = false;
      }
    });
  }

  // Kada lekar klikne na pacijenta iz liste
  selectPatient(patient: any): void {
    this.selectedPatient = patient;
    this.notes = '';
    this.medications = [];
    
    this.http.get<any>(`${this.baseUrl}/medical-record/${patient.id}`, { headers: this.getHeaders() }).subscribe({
      next: (res) => {
        console.log('Podaci sa servera:', res); // Ovo će nam ispisati u konzoli šta je tačno stiglo
        if (res.success && res.data) {
          this.notes = res.data.notes || '';
          this.medications = res.data.medications || [];

          this.cdr.detectChanges();
        }
      },
      error: (err) => {
        console.error('Greška pri preuzimanju kartona:', err);
      }
    });
  }

  // Dodavanje leka u lokalni niz
  addMedication(): void {
    if (this.newMedication.trim()) {
      this.medications.push(this.newMedication.trim());
      this.newMedication = '';
    }
  }

  // Uklanjanje leka iz lokalnog niza
  removeMedication(index: number): void {
    this.medications.splice(index, 1);
  }

  // Snimanje svega u bazu podataka
  saveMedicalRecord(): void {
    const body = {
      notes: this.notes,
      medications: this.medications
    };

    this.http.post<any>(`${this.baseUrl}/medical-record/${this.selectedPatient.id}`, body, { headers: this.getHeaders() }).subscribe({
      next: (res) => {
        if (res.success) {
          alert('Beleške i lekovi su uspešno sačuvani!');
        }
      },
      error: () => alert('Greška prilikom čuvanja podataka.')
    });
  }

  
}