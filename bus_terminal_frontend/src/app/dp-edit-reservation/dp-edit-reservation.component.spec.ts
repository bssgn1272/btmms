import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DpEditReservationComponent } from './dp-edit-reservation.component';

describe('DpEditReservationComponent', () => {
  let component: DpEditReservationComponent;
  let fixture: ComponentFixture<DpEditReservationComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DpEditReservationComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DpEditReservationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
