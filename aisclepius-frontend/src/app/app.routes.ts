import { Routes } from '@angular/router';
import { Login } from './pages/login/login';
import { Register } from './pages/register/register';
import { Dashboard } from './pages/dashboard/dashboard';
import { HeartCheck } from './pages/heart-check/heart-check';
import { DiabetesCheck } from './pages/diabetes-check/diabetes-check';
import { History } from './pages/history/history';
import { VideoRoom } from './pages/video-room/video-room';

// Uvozimo našu novu layout komponentu
import { MainLayout } from './layouts/main-layout/main-layout';

export const routes: Routes = [

    // Rute koje su van glavnog layout-a (nemaju navigaciju)
    { path: 'login', component: Login },
    { path: 'register', component: Register },

    // Glavni layout koji sadrži sve ostale stranice
    {
        path: '', // Prazna putanja, odnosi se na root
        component: MainLayout,
        children: [
            // Sve stranice unutar 'children' će se prikazati unutar MainLayoutComponent
            { path: '', redirectTo: 'dashboard', pathMatch: 'full' }, // Ako je putanja prazna, preusmeri na dashboard
            { path: 'dashboard', component: Dashboard },
            { path: 'heart-check', component: HeartCheck },
            { path: 'diabetes-check', component: DiabetesCheck },
            { path: 'history', component: History },
            { path: 'appointment/:id/room', component: VideoRoom },
        ]
    },

    // Ako nijedna ruta ne odgovara, preusmeri na login
    { path: '**', redirectTo: 'login' }
];