import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UpdateSlotTimeComponent } from './update-slot-time.component';

describe('UpdateSlotTimeComponent', () => {
  let component: UpdateSlotTimeComponent;
  let fixture: ComponentFixture<UpdateSlotTimeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UpdateSlotTimeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UpdateSlotTimeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
