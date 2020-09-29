import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ViewMyPenaltiesComponent } from './view-my-penalties.component';

describe('ViewMyPenaltiesComponent', () => {
  let component: ViewMyPenaltiesComponent;
  let fixture: ComponentFixture<ViewMyPenaltiesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ViewMyPenaltiesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ViewMyPenaltiesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
