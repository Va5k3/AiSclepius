import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaderResponse, HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs'; 


@Injectable({
  providedIn: 'root',
})
export class HeartCheckService {

  // Laravel API URL (lokalni port 8001)
  private apiUrl = 'http://localhost:8001/api/predict-heart';

  constructor(private http: HttpClient){}

  sendHeartData(data: any): Observable<any>{ 
    const token = localStorage.getItem('token');

    const headers = new HttpHeaders({
      'Content-Type' : 'application/json',
      'Authorization': `Bearer ${token}`
    });


    return this.http.post<any>(this.apiUrl,data,{headers});
  }



}
