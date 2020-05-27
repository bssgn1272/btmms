import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CancellationRequestComponent } from './cancellation-request.component';

describe('CancellationRequestComponent', () => {
  let component: CancellationRequestComponent;
  let fixture: ComponentFixture<CancellationRequestComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CancellationRequestComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CancellationRequestComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
