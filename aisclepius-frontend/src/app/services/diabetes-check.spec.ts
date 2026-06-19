import { TestBed } from '@angular/core/testing';

import { DiabetesCheck } from './diabetes-check';

describe('DiabetesCheck', () => {
  let service: DiabetesCheck;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(DiabetesCheck);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
