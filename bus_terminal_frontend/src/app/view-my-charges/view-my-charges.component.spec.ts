import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ViewMyChargesComponent } from './view-my-charges.component';

describe('ViewMyChargesComponent', () => {
  let component: ViewMyChargesComponent;
  let fixture: ComponentFixture<ViewMyChargesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ViewMyChargesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ViewMyChargesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
