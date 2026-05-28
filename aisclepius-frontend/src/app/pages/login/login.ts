import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink], // Obavezno uvezi FormsModule i RouterLink
  templateUrl: './login.html',
  styleUrl: './login.css'
})
export class Login {
  // Promenljive u koje se automatski upisuje tekst iz input polja
  email = '';
  password = '';
  errorMessage = '';

  constructor(private router: Router, private http: HttpClient) {}

  onLogin(event: Event) {
    event.preventDefault(); // Sprečavamo osvežavanje stranice
    this.errorMessage = ''; // Resetujemo poruku 

    const apiUrl = 'http://localhost:8001/api/login'; // URL vašeg Laravel API-ja

    const loginData = {
      email: this.email,
      password: this.password
    };
    console.log('Slanje na Laravel API:', loginData);

    this.http.post<any>(apiUrl, loginData).subscribe({
      next: (response) => { 
        console.log('Odgovor sa Laravel API:', response);

        if(response.success) {
          localStorage.setItem('token', response.token);
          this.router.navigate(['/dashboard']);
        }
      },
      error: (error) => {
        console.error('Greška prilikom logina:', error);
        this.errorMessage = 'Neuspešan login. Proverite email i lozinku.';
      }
    });
  }
}