import { Component, OnInit, OnDestroy, ChangeDetectorRef } from '@angular/core';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { CommonModule } from '@angular/common';

declare var JitsiMeetExternalAPI: any;

@Component({
  selector: 'app-video-room',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './video-room.html',
  styleUrl: './video-room.css',
})
export class VideoRoom implements OnInit, OnDestroy {
  appointmentId!: string;
  roomData: any = null;
  jitsiApi: any = null; // Ovde bezbedno čuvamo aktivnu instancu poziva

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private http: HttpClient,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit() {
    
    this.appointmentId = this.route.snapshot.paramMap.get('id')!;
    const token = localStorage.getItem('auth_token');
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });

    // Pozivamo Laravel i prosleđujemo taj ID
    this.http.get(`http://localhost:8001/api/appointment/${this.appointmentId}/room`, {headers}).subscribe({
      next: (res: any) => {
        this.roomData = res;
        
        // Primoravamo Angular da odmah renderuje HTML kontejner za Jitsi
        this.cdr.detectChanges();
        
        
        setTimeout(() => {
          this.initializeJitsi();
        }, 50);
      },
      error: (err) => {
        console.error('Greška pri učitavanju sobe sa bekenda:', err);
      }
    });
  }

  initializeJitsi() {
    if (!this.roomData) return;

    const domain = 'meet.jit.si';
    const options = {
      roomName: this.roomData.roomName,
      width: '100%',
      height: '100%',
      parentNode: document.querySelector('#jitsi-container'),
      userInfo: {
        displayName: this.roomData.userName || 'Korisnik'
      }
    };
    
    // ISPRAVLJENO: Sada Jitsi instancu čuvamo globalno u klasi
    this.jitsiApi = new JitsiMeetExternalAPI(domain, options);
  }

  finishAppointment(): void {
    const token = localStorage.getItem('auth_token');
    const headers = new HttpHeaders({
      'Authorization' : `Bearer ${token}`
    })

    this.http.post(`http://localhost:8001/api/appointment/${this.appointmentId}/finish`, {headers})
      .subscribe({
        next: () => {
          this.cleanUpJitsi();
          this.router.navigate(['/dashboard']);
        },
        error: (err) => console.error('Greška pri zatvaranju pregleda:', err)
      });
  }

  ngOnDestroy(): void {
    this.cleanUpJitsi();
  }

  private cleanUpJitsi(): void {
    if (this.jitsiApi) {
      this.jitsiApi.dispose();
      this.jitsiApi = null;
    }
  }
}