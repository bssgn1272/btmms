import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RejectArrivalComponent } from './reject-arrival.component';

describe('RejectArrivalComponent', () => {
  let component: RejectArrivalComponent;
  let fixture: ComponentFixture<RejectArrivalComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RejectArrivalComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RejectArrivalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
