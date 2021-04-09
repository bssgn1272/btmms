import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChangeOptionComponent } from './change-option.component';

describe('ChangeOptionComponent', () => {
  let component: ChangeOptionComponent;
  let fixture: ComponentFixture<ChangeOptionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChangeOptionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChangeOptionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
