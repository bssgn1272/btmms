import { TestBed, inject } from '@angular/core/testing';

import { ViewMyPenaltiesService } from './view-my-penalties.service';

describe('ViewMyPenaltiesService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [ViewMyPenaltiesService]
    });
  });

  it('should be created', inject([ViewMyPenaltiesService], (service: ViewMyPenaltiesService) => {
    expect(service).toBeTruthy();
  }));
});
