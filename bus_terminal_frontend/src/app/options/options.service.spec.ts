import { TestBed, inject } from '@angular/core/testing';

import { OptionsService } from './options.service';

describe('OptionsService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [OptionsService]
    });
  });

  it('should be created', inject([OptionsService], (service: OptionsService) => {
    expect(service).toBeTruthy();
  }));
});
