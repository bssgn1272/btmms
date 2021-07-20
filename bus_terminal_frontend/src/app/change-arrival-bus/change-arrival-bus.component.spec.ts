import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChangeArrivalBusComponent } from './change-arrival-bus.component';

describe('ChangeBusComponent', () => {
  let component: ChangeArrivalBusComponent;
  let fixture: ComponentFixture<ChangeArrivalBusComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChangeArrivalBusComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChangeArrivalBusComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
