import { Component, ChangeDetectorRef, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { Router, RouterLink } from '@angular/router';
import { HeartCheckService } from '../../services/heart-check';

@Component({
  selector: 'app-heart-check',
  imports: [FormsModule, CommonModule, RouterLink],
  standalone: true,
  templateUrl: './heart-check.html',
  styleUrl: './heart-check.css',
})
export class HeartCheck implements OnInit{

  heartData = {
    age: null,
    sex: '',       
    cp: null,       // Tip bola u grudima 
    trestbps: null, // Krvni pritisak u mirovanju 
    chol: null,     // holesterol
    fbs: false,     // secer u krvi <120
    restecg: null,  // EKG u mirovanju (0-2)
    thalach: null,  // Maksimalan puls
    exang: false,   // Checkbox: Angina izazvana vezbanjem
    oldpeak: null,  // ST depresija (decimalni broj)
    slope: null,    // Nagib ST segmenta (1-3)
    ca: null,       // Broj obojenih krvnih sudova (0-3)
    thal: null      // Defekt (3, 6, 7)
  };
  isLoggedIn : boolean = false;
  isLoading = false; // animacija ucitavanja
  apiResult: any = null; 
  errorMessage = '';
  showGif = false;

  // 1. Ubacujemo ChangeDetectorRef u konstruktor
  constructor(
    private heartCheckService: HeartCheckService,
    private cdr: ChangeDetectorRef
  ) {}


  ngOnInit(){
    this.isLoggedIn = !!localStorage.getItem('auth_token');
  }
  
  onSubmit(event: Event) {
    console.log('Dugme je uspešno kliknuto!');
    event.preventDefault();
    this.isLoading = true;
    this.errorMessage = '';
    this.apiResult = null;
    this.showGif = true;

    console.log('Saljem podatke na analizu: ', this.heartData);

    this.heartCheckService.sendHeartData(this.heartData).subscribe({
      next: (response) => {
        this.isLoading = false;
        this.apiResult = response; // Cuvamo uspešan rezultat (procena rizika)
        console.log("Rezultat iz Laravela : ", response);
        
         //sound
        if (response && response.success) {
        this.showGif = true;
        // Ako je rizik 1 
        if (response.risk === 1) {
          const sadAudio = new Audio('sounds/sadShort2.mp3');
          sadAudio.play().catch(err => console.log("Audio bloker u brauzeru:", err));
        } 
        // Ako je rizik 0 
        else {
          const happyAudio = new Audio('sounds/rizz.mp3');
          happyAudio.play().catch(err => console.log("Audio bloker u brauzeru:", err));
          }
          setTimeout(() => {
            this.showGif = false; 
            this.cdr.detectChanges(); // Javlja Angularu da skloni medu sa ekrana
            }, 3000);
            }
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