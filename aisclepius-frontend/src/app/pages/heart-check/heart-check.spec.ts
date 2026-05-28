import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HeartCheck } from './heart-check';

describe('HeartCheck', () => {
  let component: HeartCheck;
  let fixture: ComponentFixture<HeartCheck>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [HeartCheck],
    }).compileComponents();

    fixture = TestBed.createComponent(HeartCheck);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
