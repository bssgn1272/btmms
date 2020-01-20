import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DestinationDayComponent } from './destination-day.component';

describe('DestinationDayComponent', () => {
  let component: DestinationDayComponent;
  let fixture: ComponentFixture<DestinationDayComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DestinationDayComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DestinationDayComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
