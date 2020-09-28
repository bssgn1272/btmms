import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChangeVehicleComponent } from './change-vehicle.component';

describe('ChangeVehicleComponent', () => {
  let component: ChangeVehicleComponent;
  let fixture: ComponentFixture<ChangeVehicleComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChangeVehicleComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChangeVehicleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
