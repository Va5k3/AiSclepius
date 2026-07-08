import { Routes } from '@angular/router';
import { LoginComponent } from './pages/login/login';
import { RegisterComponent } from './pages/register/register';
import { Dashboard } from './pages/dashboard/dashboard';
import { DoctorDashboardComponent } from './pages/doctor-dashboard/doctor-dashboard.page';
import { HeartCheck } from './pages/heart-check/heart-check';
import { DiabetesCheck } from './pages/diabetes-check/diabetes-check';
import { History } from './pages/history/history';
import { VideoRoom } from './pages/video-room/video-room';

import { authGuard, roleGuard } from './gurads/auth-guard';


import { MainLayout } from './layouts/main-layout/main-layout';

export const routes: Routes = [

    // Rute za goste
    { path: 'login', component: LoginComponent },
    { path: 'register', component: RegisterComponent },

    // zasticene rute (zahtevaju login)
    {
        path: '', 
        component: MainLayout,
        children: [
            { path: '', redirectTo: 'dashboard', pathMatch: 'full' }, 
            
            { path: 'dashboard', component: Dashboard },
            

            // Lekarske rute (Pacijent ovde nema pristup)
            { 
              path: 'doctor-dashboard', 
              component: DoctorDashboardComponent, 
              canActivate: [roleGuard], 
              data: { roles: ['doctor'] } 
            },

            // zajednicke rute
            { 
              path: 'history', 
              component: History, 
              canActivate: [roleGuard], 
              data: { roles: ['patient', 'doctor'] } 
            },
            { 
              path: 'appointment/1/room', 
              component: VideoRoom, 
              canActivate: [roleGuard], 
              data: { roles: ['patient', 'doctor'] } 
            },
            { 
              path: 'heart-check', 
              component: HeartCheck, 
              canActivate: [roleGuard], 
              data: { roles: ['patient', 'doctor'] } 
            },
            { 
              path: 'diabetes-check', 
              component: DiabetesCheck, 
              canActivate: [roleGuard], 
              data: { roles: ['patient', 'doctor'] } 
            },
        ]
    },

    { path: '**', redirectTo: 'login' }
];