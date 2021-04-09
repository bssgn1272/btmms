import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DueTimeComponent } from './due-time.component';

describe('DueTimeComponent', () => {
  let component: DueTimeComponent;
  let fixture: ComponentFixture<DueTimeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DueTimeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DueTimeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
