import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { AuthService } from '../../services/auth';


@Component({
  selector: 'app-doctor-dashboard',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './doctor-dashboard.page.html',
  styleUrls: ['./doctor-dashboard.page.css'] // promeni u .scss ako koristiš sass
})
export class DoctorDashboardComponent {

  userName : string | null = null;
  
  
  constructor(private authService: AuthService){
    this.userName = this.authService.getName();
  }


  getDashboardName() : string | null{
    return this.userName;
  }



}