import { Component, ChangeDetectorRef } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { HeartCheckService } from '../../services/heart-check';

@Component({
  selector: 'app-heart-check',
  imports: [FormsModule, CommonModule],
  standalone: true,
  templateUrl: './heart-check.html',
  styleUrl: './heart-check.css',
})
export class HeartCheck {

  heartData = {
    age: null,
    gender: '',
    systolic_bp: null,
    diastolic_bp: null,
    cholesterol: null,
    bmi: null,
    smoking: false,
    family_history: false
  };

  isLoading = false; // animacija ucitavanja
  apiResult: any = null; 
  errorMessage = '';

  // 1. Ubacujemo ChangeDetectorRef u konstruktor
  constructor(
    private heartCheckService: HeartCheckService,
    private cdr: ChangeDetectorRef
  ) {}
  
  onSubmit(event: Event) {
    console.log('Dugme je uspešno kliknuto!');
    event.preventDefault();
    this.isLoading = true;
    this.errorMessage = '';
    this.apiResult = null;

    console.log('Saljem podatke na analizu: ', this.heartData);

    this.heartCheckService.sendHeartData(this.heartData).subscribe({
      next: (response) => {
        this.isLoading = false;
        this.apiResult = response; // Cuvamo uspešan rezultat (procena rizika)
        console.log("Rezultat iz Laravela : ", response);
        
        // 2. Ručno pokrećemo osvežavanje HTML šablona
        this.cdr.detectChanges();
      },
      error: (err) => {
        this.isLoading = false;
        this.errorMessage = "Došlo je do greške prilikom analize, proverite konekciju sa serverom";
        console.error(err);
        
        // Takođe osvežavamo šablon u slučaju greške da se ugasi loader
        this.cdr.detectChanges();
      }
    });
  }
}