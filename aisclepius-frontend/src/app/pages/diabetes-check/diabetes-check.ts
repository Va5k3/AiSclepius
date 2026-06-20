import { Component, ChangeDetectorRef } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { DiabetesCheckService } from '../../services/diabetes-check';

@Component({
  selector: 'app-diabetes-check',
  imports: [FormsModule, CommonModule],
  standalone: true,
  templateUrl: './diabetes-check.html',
  styleUrl: './diabetes-check.css',
})
export class DiabetesCheck {

  diabetesData = {
    pregnacies: null,
    glucose : null,
    bloodPressure : null,
    skinThickness : null,
    insulin : null,
    bmi : null,
    dpf : null,
    age : null
  };


  isLoading = false;
  apiResult: any = null;
  errorMessage = '';
  showGif = false;

  constructor(
    private diabetesCheckService : DiabetesCheckService,
    private cdr: ChangeDetectorRef
  ){}

  onSubmti(event: Event){
    console.log('Dugme je uspesno kliknuto!');
    event.preventDefault();
    this.isLoading = true;
    this.errorMessage = '';
    this.apiResult = null;

    console.log("Saljem podatke na analizu: ", this.diabetesData);

    this.diabetesCheckService.sendDiabatesData(this.diabetesData).subscribe({
      next: (response) =>{
        this.isLoading = false;
        this.apiResult = response;
        console.log("Rezultat iz laravel : ", response);

        //sound
        if (response && response.success) {
        this.showGif = true;
        // Ako je rizik 1 
        if (response.risk === 1) {
          const sadAudio = new Audio('sounds/sadShort.mp3');
          sadAudio.play().catch(err => console.log("Audio bloker u brauzeru:", err));
        } 
        // Ako je rizik 0 
        else {
          const happyAudio = new Audio('sounds/happy2.mp3');
          happyAudio.play().catch(err => console.log("Audio bloker u brauzeru:", err));
          }
          setTimeout(() => {
            this.showGif = false; 
            this.cdr.detectChanges(); // Javlja Angularu da skloni medu sa ekrana
            }, 3000);
            }

        this.cdr.detectChanges();
      },
      error: (err) =>{
        this.isLoading = false;
        this.errorMessage = "Doslo je do greske prilikom analize, proverite konekciju sa serverom";
        console.error(err);

        this.cdr.detectChanges();
      }
    });
  }
}
