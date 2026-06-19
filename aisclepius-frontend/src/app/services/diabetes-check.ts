import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaderResponse, HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs'; 


@Injectable({
  providedIn: 'root',
})
export class DiabetesCheckService {

  private apiUrl = 'http://localhost:8001/api/predict-diabetes';


  constructor(private http : HttpClient){}

  sendDiabatesData(data: any): Observable<any>{
    const token = localStorage.getItem('token');

    const headers = new HttpHeaders({
      'Content-Type' : 'application/json',
      'Authorization' : `Bearer ${token}`
    });


    return this.http.post<any>(this.apiUrl,data,{headers});
  }




}
