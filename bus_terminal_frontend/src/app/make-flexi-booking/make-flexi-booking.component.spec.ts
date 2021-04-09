import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MakeFlexiBookingComponent } from './make-flexi-booking.component';

describe('MakeFlexiBookingComponent', () => {
  let component: MakeFlexiBookingComponent;
  let fixture: ComponentFixture<MakeFlexiBookingComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MakeFlexiBookingComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MakeFlexiBookingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
