import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CancelArrivalReservationComponent } from './cancel-arrival-reservation.component';

describe('CancelArrivalReservationComponent', () => {
  let component: CancelArrivalReservationComponent;
  let fixture: ComponentFixture<CancelArrivalReservationComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CancelArrivalReservationComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CancelArrivalReservationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
