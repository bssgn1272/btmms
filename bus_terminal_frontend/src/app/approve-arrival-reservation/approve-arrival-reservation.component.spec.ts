import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ApproveArrivalReservationComponent } from './approve-arrival-reservation.component';

describe('ApproveArrivalReservationComponent', () => {
  let component: ApproveArrivalReservationComponent;
  let fixture: ComponentFixture<ApproveArrivalReservationComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ApproveArrivalReservationComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ApproveArrivalReservationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
