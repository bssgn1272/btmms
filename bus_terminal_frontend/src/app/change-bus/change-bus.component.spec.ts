import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChangeBusComponent } from './change-bus.component';

describe('ChangeBusComponent', () => {
  let component: ChangeBusComponent;
  let fixture: ComponentFixture<ChangeBusComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChangeBusComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChangeBusComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
