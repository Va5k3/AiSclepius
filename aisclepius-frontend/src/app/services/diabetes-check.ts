import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaderResponse, HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs'; 
import { environment } from '../../environments/environment';


@Injectable({
  providedIn: 'root',
})
export class DiabetesCheckService {

  private apiUrl = `${environment.apiBaseUrl}/predict-diabetes`;


  constructor(private http : HttpClient){}

  sendDiabatesData(data: any): Observable<any>{
    const token = localStorage.getItem('auth_token');

    const headers = new HttpHeaders({
      'Content-Type' : 'application/json',
      'Authorization' : `Bearer ${token}`
    });


    return this.http.post<any>(this.apiUrl,data,{headers});
  }




}
