import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpClient, HttpHeaders } from '@angular/common/http';

@Component({
  selector: 'app-history',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './history.html',
  styleUrl: './history.css' 
})
export class History implements OnInit {
  historyList: any[] = [];
  isLoading = true;
  errorMessage = '';
  
  private apiUrl = 'http://localhost:8001/api/history'; // Prilagodi port ako je drugačiji

  constructor(private http: HttpClient, private cdr: ChangeDetectorRef) {}

  ngOnInit(): void {
    this.loadHistory();
  }

  loadHistory(): void {
    const token = localStorage.getItem('auth_token');
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });

    console.log("Saljem zahtev za istoriju na : ", this.apiUrl);

    this.isLoading = true;
    this.http.get<any>(this.apiUrl, { headers }).subscribe({
      next: (response) => {
        this.isLoading = false;
        console.log("Odgovor sa laravela za istoriju : ", response);
        if (response.success) {
          this.historyList = response.history;
        } else {
          this.errorMessage = 'Nije moguće učitati istoriju.';
        }
        this.cdr.detectChanges();
      },
      error: (err) => {
        this.isLoading = false;
        this.errorMessage = 'Greška u komunikaciji sa serverom.';
        console.error(err);
        this.cdr.detectChanges();
      }
    });
  }
}