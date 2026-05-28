import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DiabetesCheck } from './diabetes-check';

describe('DiabetesCheck', () => {
  let component: DiabetesCheck;
  let fixture: ComponentFixture<DiabetesCheck>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [DiabetesCheck],
    }).compileComponents();

    fixture = TestBed.createComponent(DiabetesCheck);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
