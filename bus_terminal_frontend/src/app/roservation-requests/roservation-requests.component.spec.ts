import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RoservationRequestsComponent } from './roservation-requests.component';

describe('RoservationRequestsComponent', () => {
  let component: RoservationRequestsComponent;
  let fixture: ComponentFixture<RoservationRequestsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RoservationRequestsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RoservationRequestsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
