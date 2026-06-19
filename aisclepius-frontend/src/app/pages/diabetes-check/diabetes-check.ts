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
