import { TestBed } from '@angular/core/testing';

import { HeartCheckService } from './heart-check';

describe('HeartCheckService', () => {
  let service: HeartCheckService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(HeartCheckService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
