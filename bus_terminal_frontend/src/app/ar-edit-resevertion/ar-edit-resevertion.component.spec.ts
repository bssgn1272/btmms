import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ArEditResevertionComponent } from './ar-edit-resevertion.component';

describe('ArEditResevertionComponent', () => {
  let component: ArEditResevertionComponent;
  let fixture: ComponentFixture<ArEditResevertionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ArEditResevertionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ArEditResevertionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
