import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, tap } from 'rxjs';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = environment.apiBaseUrl;

  constructor(private http: HttpClient) {}

  // LOGIN METODA
  login(credentials: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/login`, credentials).pipe(
      tap((res: any) => {
        if (res.success && res.token) {
          this.saveSession(res.token, res.role, res.user, res.name);
        }
      })
    );
  }

  // REGISTRACIJA METODA
  register(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/register`, data).pipe(
      tap((res: any) => {
        if (res.success && res.token) {
          this.saveSession(res.token, res.role, res.user, res.name);
        }
      })
    );
  }

  // Privatna funkcija koja skladišti podatke u memoriju browsera
  private saveSession(token: string, role: string, user: any, name: string) {
    localStorage.setItem('auth_token', token);
    localStorage.setItem('user_role', role);
    localStorage.setItem('name', name)
    localStorage.setItem('user_data', JSON.stringify(user));
  }

  // Odjava sa platforme
  logout() {
    localStorage.clear();
  }

  // Brze provere uloga za rute i interfejs
  isLoggedIn(): boolean {
    return !!localStorage.getItem('auth_token');
  }

  getRole(): 'patient' | 'doctor' | string | null {
    return localStorage.getItem('user_role');
  }

  getName() : string | null {
    return localStorage.getItem('name');
  }

}