import { inject } from '@angular/core';
import { Router } from '@angular/router';

// 1. ZAKLJUČAVANJE ZA GOSTE: Ne da nikome bez tokena da uđe na zaštićene stranice
export const authGuard = () => {
  const router = inject(Router);
  const token = localStorage.getItem('auth_token');

  if (token) {
    return true; // Ulogovan je, pusti ga
  }

  router.navigate(['/login']); // Nije ulogovan, baci ga na login
  return false;
};

// 2. ZAKLJUČAVANJE PO ULOGAMA: Ne da pacijentu kod lekara i obrnuto
export const roleGuard = (route: any) => {
  const router = inject(Router);
  const userRole = localStorage.getItem('user_role'); // Pretpostavljamo da čuvaš ulogu ('patient' ili 'doctor') pri login-u
  const expectedRoles = route.data['roles'] as Array<string>;

  if (userRole && expectedRoles.includes(userRole)) {
    return true; // Ima odgovarajuću ulogu, pusti ga
  }

  // Ako lekar zaluta na pacijentske stranice ili obrnuto, vrati ih tamo gde pripadaju
  if (userRole === 'doctor') {
    router.navigate(['/doctor-dashboard']);
  } else {
    router.navigate(['/dashboard']);
  }
  return false;
};