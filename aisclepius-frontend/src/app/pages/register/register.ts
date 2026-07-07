import { Component, ChangeDetectorRef} from '@angular/core';
import { AuthService } from '../../services/auth';
import { Router, RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common'; 
import { FormsModule } from '@angular/forms';     

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink],
  templateUrl: './register.html'
})
export class RegisterComponent {
  registerData = {
    name: '',
    email: '',
    password: '',
    password2: '',
    role: 'patient' // default pacijent
  };
  errorMessage = '';

  constructor(private authService: AuthService, private router: Router, private cdr : ChangeDetectorRef) {}

  onRegister() {
    this.errorMessage = '';
    if(this.registerData.password==this.registerData.password2){
    this.authService.register(this.registerData).subscribe({
      next: (res) => {
        if (res.success) {
          if (res.role === 'doctor') {
            this.router.navigate(['/doctor-dashboard']);
          } else {
            this.router.navigate(['/diabetes-check']);
          }
        }
      },
      error: (err) => {
        this.errorMessage = err.error?.message || 'Greška prilikom registracije.';
        this.cdr.detectChanges();
      }
    });
  }
  else{
        this.errorMessage =  'Lozinke se ne poklapaju!';
        this.cdr.detectChanges();
  }
  }
}