import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ViewMySlotsComponent } from './view-my-slots.component';

describe('ViewMySlotsComponent', () => {
  let component: ViewMySlotsComponent;
  let fixture: ComponentFixture<ViewMySlotsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ViewMySlotsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ViewMySlotsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
