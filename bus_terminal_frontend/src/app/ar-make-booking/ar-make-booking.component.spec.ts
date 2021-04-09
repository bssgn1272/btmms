import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ArMakeBookingComponent } from './ar-make-booking.component';

describe('ArMakeBookingComponent', () => {
  let component: ArMakeBookingComponent;
  let fixture: ComponentFixture<ArMakeBookingComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ArMakeBookingComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ArMakeBookingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
