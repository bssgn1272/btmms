import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FlexiBookingComponent } from './flexi-booking.component';

describe('FlexiBookingComponent', () => {
  let component: FlexiBookingComponent;
  let fixture: ComponentFixture<FlexiBookingComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FlexiBookingComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FlexiBookingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
